<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TicketController extends Controller
{
    public function generateTicket($orderId)
    {
        // Giả sử bạn có model Order
        $order = \App\Models\Order::findOrFail($orderId);

        // Sinh mã QR (VD: thông tin đặt vé hoặc mã vé)
        $qrCode = base64_encode(QrCode::format('png')->size(150)->generate($order->id));

        // Dữ liệu đưa vào file PDF
        $data = [
            'order' => $order,
            'qrCode' => $qrCode,
        ];

        // Tạo PDF từ view
        $pdf = Pdf::loadView('tickets.ticket_pdf', $data);

        // Xuất ra trình duyệt
        return $pdf->download('ticket_' . $order->id . '.pdf');
    }
}
