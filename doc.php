<?php

use Dompdf\Dompdf;
use Dompdf\Options;

require 'vendor/autoload.php';


// Invoice data
$invoiceData = [
    'company' => [
        'name' => 'DESIGN ART',
        'tagline' => 'Our Creative Vision',
        'address' => 'Narayanganj, Dhaka Bangladesh',
        'phone' => '+88 01970091858',
        'email' => 'yourgmail.com',
        'website' => 'www.google.com'
    ],
    'client' => [
        'name' => 'MR. MICHEL BEEN',
        'address' => 'Dhanmondi Section 2,',
        'city' => 'Dhaka Bangladesh',
        'phone' => '+88 0100091858',
        'website' => 'www.google.com'
    ],
    'invoice' => [
        'number' => '12345',
        'date' => '01.10.2030',
        'due_date' => '20.10.2020',
        'account_no' => '12345678'
    ],
    'items' => [
        ['Packaging Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore', 2, 130.00, 260.00],
        ['Social Media Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore', 3, 120.00, 360.00],
        ['Logo Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore', 2, 150.00, 300.00],
        ['Banner Design', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore', 5, 100.00, 500.00],
    ],
    'subtotal' => 1420.00,
    'tax' => 282.00,
    'discount' => 100.00,
    'total' => 1602.00,
    'payment_methods' => ['Pypal', 'Skril'],
    'terms' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exerci tation ullamcorper suscipit lobortis nisl ut aliquip ex ea commodo consequat.',
    'signature' => 'Jon Smith',
    'signature_title' => 'General Manager'
];

// HTML template
$html = '
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        .invoice-header {
            background-color: #f26522;
            color: white;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .invoice-body {
            margin-top: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f26522;
            color: white;
        }
        .total-section {
            margin-top: 20px;
            text-align: right;
        }
        .payment-terms {
            margin-top: 20px;
        }
        .signature {
            margin-top: 40px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <div>
            <h1>' . $invoiceData['company']['name'] . '</h1>
            <p>' . $invoiceData['company']['tagline'] . '</p>
            <p>' . $invoiceData['company']['address'] . '</p>
            <p>Phone: ' . $invoiceData['company']['phone'] . '</p>
            <p>Email: ' . $invoiceData['company']['email'] . '</p>
            <p>Website: ' . $invoiceData['company']['website'] . '</p>
        </div>
        <div>
            <h2>INVOICE</h2>
            <p>Invoice No: ' . $invoiceData['invoice']['number'] . '</p>
            <p>Date: ' . $invoiceData['invoice']['date'] . '</p>
            <p>Due Date: ' . $invoiceData['invoice']['due_date'] . '</p>
            <p>Account No: ' . $invoiceData['invoice']['account_no'] . '</p>
        </div>
    </div>
    <div class="invoice-body">
        <h3>Invoice To:</h3>
        <p>' . $invoiceData['client']['name'] . '</p>
        <p>' . $invoiceData['client']['address'] . '</p>
        <p>' . $invoiceData['client']['city'] . '</p>
        <p>Phone: ' . $invoiceData['client']['phone'] . '</p>
        <p>Website: ' . $invoiceData['client']['website'] . '</p>
        
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Item Description</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>';

foreach ($invoiceData['items'] as $index => $item) {
    $html .= '
                <tr>
                    <td>' . ($index + 1) . '</td>
                    <td>
                        <strong>' . $item[0] . '</strong><br>
                        ' . $item[1] . '
                    </td>
                    <td>' . $item[2] . '</td>
                    <td>$' . number_format($item[3], 2) . '</td>
                    <td>$' . number_format($item[4], 2) . '</td>
                </tr>';
}

$html .= '
            </tbody>
        </table>
        
        <div class="total-section">
            <p>Subtotal: $' . number_format($invoiceData['subtotal'], 2) . '</p>
            <p>Tax (20%): $' . number_format($invoiceData['tax'], 2) . '</p>
            <p>Discount: $' . number_format($invoiceData['discount'], 2) . '</p>
            <h3>Grand Total: $' . number_format($invoiceData['total'], 2) . '</h3>
        </div>
        
        <div class="payment-terms">
            <h3>Payment Method</h3>
            <p>' . implode(', ', $invoiceData['payment_methods']) . '</p>
            
            <h3>Terms and Conditions</h3>
            <p>' . $invoiceData['terms'] . '</p>
        </div>
        
        <div class="signature">
            <p>' . $invoiceData['signature'] . '</p>
            <p>' . $invoiceData['signature_title'] . '</p>
        </div>
    </div>
</body>
</html>
';

// Create PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$options->set('isPhpEnabled', true);

$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
$dompdf->stream("invoice.pdf", array("Attachment" => false));