<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class VnpayController extends Controller
{
    private const VNP_URL         = 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html';
    private const VNP_RETURN_URL  = '/vnpay/return'; 
    private const VNP_TMN_CODE    = '9B356UF8';
    private const VNP_HASH_SECRET = '9LNWW09XJSAT2H6H89DA71Q0FD6O92NS';

    /**
     * GET /vnpay/create?amount=123000[&bankcode=NCB]
     * Tạo URL thanh toán và chuyển hướng sang VNPAY.
     */
    public function create(Request $request)
    {
        $orderId = (int) $request->query('order_id', 0);
        $order = Order::query()
            ->where('id', $orderId)
            ->where('user_id', auth('web')->id())
            ->first();

        if (!$order) {
            return redirect()->route('web.error.order')->with('error', 'Không tìm thấy đơn hàng để thanh toán.');
        }

        $amount = (int) $order->total();
        if ($amount <= 0) {
            return redirect()->route('web.error.order')->with('error', 'Số tiền thanh toán không hợp lệ.');
        }

        $vnp_TxnRef     = (string) $order->id;
        $vnp_OrderInfo  = 'Thanh toán đơn hàng';
        $vnp_OrderType  = 'billpayment';
        $vnp_Amount     = $amount * 100; 
        $vnp_Locale     = 'vn';
        $vnp_IpAddr     = $request->ip();
        $vnp_ReturnUrl  = url(self::VNP_RETURN_URL); 
        $vnp_BankCode   = $request->query('bankcode'); 

        $input = [
            'vnp_Version'    => '2.1.0',
            'vnp_TmnCode'    => self::VNP_TMN_CODE,
            'vnp_Amount'     => $vnp_Amount,
            'vnp_Command'    => 'pay',
            'vnp_CreateDate' => now()->format('YmdHis'),
            'vnp_CurrCode'   => 'VND',
            'vnp_IpAddr'     => $vnp_IpAddr,
            'vnp_Locale'     => $vnp_Locale,
            'vnp_OrderInfo'  => $vnp_OrderInfo,
            'vnp_OrderType'  => $vnp_OrderType,
            'vnp_ReturnUrl'  => $vnp_ReturnUrl, 
            'vnp_TxnRef'     => $vnp_TxnRef,
        ];
        if (!empty($vnp_BankCode)) {
            $input['vnp_BankCode'] = $vnp_BankCode;
        }

        // Ký tham số
        ksort($input);
        $query    = [];
        $hashData = [];
        foreach ($input as $k => $v) {
            $query[]    = urlencode($k) . '=' . urlencode($v);
            $hashData[] = urlencode($k) . '=' . urlencode($v);
        }
        $secureHash = hash_hmac('sha512', implode('&', $hashData), self::VNP_HASH_SECRET);

        $redirectUrl = self::VNP_URL . '?' . implode('&', $query) . '&vnp_SecureHash=' . $secureHash;

        return redirect()->away($redirectUrl);
    }


    public function return(Request $request)
    {
        $params = $request->query();

        $vnp_SecureHash = $params['vnp_SecureHash'] ?? '';
        unset($params['vnp_SecureHash'], $params['vnp_SecureHashType']);

        ksort($params);
        $hashPieces = [];
        foreach ($params as $k => $v) {
            $hashPieces[] = urlencode($k) . '=' . urlencode($v);
        }
        $myHash = hash_hmac('sha512', implode('&', $hashPieces), self::VNP_HASH_SECRET);

        // Lấy orderId từ vnp_TxnRef
        $orderId = $request->query('vnp_TxnRef');
        $code = $request->query('vnp_ResponseCode'); // '00' = Thành công

        // Kiểm tra chữ ký
        if (!hash_equals($myHash, $vnp_SecureHash)) {
            return redirect()->route('web.error.order')->with('error', 'Chữ ký không hợp lệ!');
        }

        // Cập nhật trạng thái thanh toán nếu thành công
        if ($code === '00' && $orderId) {
            \DB::table('orders')
                ->where('id', $orderId)
                ->update([
                    'payment_status' => 'PAID',
                    'payment_response' => json_encode($params),
                    'success_at' => now(),
                ]);
            return redirect()->route('web.success.order')->with('success', 'Thanh toán VNPAY thành công!');
        }

        return redirect()->route('web.error.order')->with('error', 'Thanh toán thất bại/đã hủy (mã: ' . $code . ').');
    }
}
