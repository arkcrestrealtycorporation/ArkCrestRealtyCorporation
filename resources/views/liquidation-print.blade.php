@extends('layouts.print-layout')

@section('content')
<div style="max-width: 1200px; margin: 0 auto;">
    @foreach($groupedRequests as $controlNumber => $requests)
        @php
            $firstRequest = $requests->first();
            $deptMap = [
                'ADMIN' => 'ADMINISTRATIVE',
                'SALES & MARKETING' => 'SALES & MARKETING',
                'HR' => 'HUMAN RESOURCES',
                'FINANCE' => 'FINANCE',
                'EXECUTIVE' => 'EXECUTIVE'
            ];
            $department = $deptMap[strtoupper($firstRequest->department)] ?? strtoupper($firstRequest->department);
            $totalExpenses = $requests->sum('total_expenses');
            $requestedAmount = $requests->sum('requested_amount');
            $amountReturned = $requestedAmount - $totalExpenses;
        @endphp
        
        <div class="form-container {{ !$loop->last ? 'page-break' : '' }}">
            <!-- Header -->
            <div class="header-section">
                <div class="logo-and-title">
                    <div class="logo-circle">
                        <img src="{{ asset('images/ArkCrest_Logo.png') }}" alt="Logo">
                    </div>
                    <div class="title-block">
                        <div class="dept-title">{{ $department }} DEPARTMENT</div>
                        <div class="form-subtitle">BUDGET REQUEST FORM</div>
                    </div>
                </div>
                <div class="control-num">Control Number: {{ $controlNumber }}</div>
            </div>
            
            <!-- Top Table -->
            <table class="info-table">
                <tr>
                    <td class="label-col">Name:</td>
                    <td class="data-col" colspan="3">{{ $firstRequest->requestor_name }}</td>
                    <td class="label-col">Date Requested:</td>
                    <td class="data-col">{{ $firstRequest->date_requested ? $firstRequest->date_requested->format('m/d/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Amount Requested:</td>
                    <td class="data-col"><strong>₱ {{ number_format($requestedAmount, 2) }}</strong></td>
                    <td class="label-col" colspan="2">Target Date Released:</td>
                    <td class="data-col" colspan="2">{{ $firstRequest->date_released ? $firstRequest->date_released->format('m/d/Y') : '' }}</td>
                </tr>
                <tr>
                    <td class="label-col">Particular :</td>
                    <td class="data-col" colspan="3">{{ $firstRequest->category }}</td>
                    <td class="label-col">Remarks:</td>
                    <td class="data-col"></td>
                </tr>
            </table>
            
            <!-- Note -->
            <div class="note-text">
                <strong>Note:</strong> (a) For amount less than <strong>₱ 1,000.00</strong> disbursement will be processed with in the day<br>
                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(b) Amount of <strong>₱1,000.00</strong> or more than will be disbursed at least one week after the submission.
            </div>
            
            <div class="divider-line"></div>
            
            <!-- Liquidation Report -->
            <div class="report-header">LIQUIDATION REPORT</div>
            
            <table class="report-table">
                <thead>
                    <tr>
                        <th style="width: 12%;">DATE</th>
                        <th style="width: 20%;">RECEIPT/INVOICE NO.</th>
                        <th style="width: 48%;">PARTICULARS</th>
                        <th style="width: 20%;">AMOUNT</th>
                    </tr>
                </thead>
                <tbody>
                    @for($i = 0; $i < 14; $i++)
                        @if($i < count($requests))
                            @php
                                $request = $requests[$i];
                            @endphp
                            <tr>
                                <td>{{ $request->date_released ? $request->date_released->format('m/d/Y') : '' }}</td>
                                <td></td>
                                <td>{{ $request->category }}</td>
                                <td class="amount-col">₱ {{ $request->total_expenses ? number_format($request->total_expenses, 2) : '' }}</td>
                            </tr>
                        @else
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td class="amount-col">₱</td>
                            </tr>
                        @endif
                    @endfor
                </tbody>
            </table>
            
            <!-- Summary -->
            <table class="summary-table">
                <tr>
                    <td class="summary-cell"><strong>TOTAL EXPENSES: ₱ {{ number_format($totalExpenses, 2) }}</strong></td>
                    <td class="summary-cell"><strong>LESS CASH ADVANCE: ₱</strong></td>
                </tr>
                <tr>
                    <td class="summary-cell" colspan="2"><strong>AMOUNT TO BE RETURNED: ₱ {{ number_format($amountReturned, 2) }}</strong></td>
                </tr>
            </table>
            
            <!-- Certification -->
            <p class="cert-text">This is to certify that the foregoing expenses were disbursed in conformity with the above stated purpose(s).</p>
            
            <!-- Signatures -->
            <table class="sig-table">
                <tr>
                    <td class="sig-col">
                        <div class="sig-title">Checked & Approved by:</div>
                        <div class="sig-space"></div>
                        <div class="sig-name"><strong>EDWIN MOJICA</strong></div>
                        <div class="sig-date">Date: _______________</div>
                    </td>
                    <td class="sig-col">
                        <div class="sig-title">Released by:</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">&nbsp;</div>
                        <div class="sig-date">Date: _______________</div>
                    </td>
                    <td class="sig-col">
                        <div class="sig-title">Received by:</div>
                        <div class="sig-space"></div>
                        <div class="sig-name">&nbsp;</div>
                        <div class="sig-date">Date: _______________</div>
                    </td>
                </tr>
            </table>
        </div>
    @endforeach
</div>

<style>
    @page { 
        size: letter; 
        margin: 0.5in; 
    }
    
    .page-break { 
        page-break-after: always; 
    }
    
    .form-container { 
        width: 100%; 
        background: white;
        margin-bottom: 30px;
        padding: 30px 40px;
        font-family: Arial, sans-serif;
    }
    
    /* Header */
    .header-section {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .logo-and-title {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .logo-circle {
        width: 85px;
        height: 85px;
        border: 3px solid #2c4a6b;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 8px;
        flex-shrink: 0;
    }
    
    .logo-circle img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }
    
    .title-block {
        display: flex;
        flex-direction: column;
        gap: 2px;
    }
    
    .dept-title {
        font-size: 19px;
        font-weight: bold;
        text-decoration: underline;
        color: #000;
        letter-spacing: 0.3px;
    }
    
    .form-subtitle {
        font-size: 17px;
        font-weight: bold;
        color: #4A7FC4;
        letter-spacing: 0.3px;
    }
    
    .control-num {
        color: #d32f2f;
        font-weight: bold;
        font-size: 10px;
        padding-top: 5px;
    }
    
    /* Tables */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }
    
    table, th, td {
        border: 1.5px solid #000;
    }
    
    th, td {
        padding: 5px 8px;
        text-align: left;
        font-size: 10px;
    }
    
    .info-table .label-col {
        font-weight: bold;
        width: 18%;
    }
    
    .info-table .data-col {
    }
    
    /* Note */
    .note-text {
        color: #d32f2f;
        font-size: 9px;
        margin: 10px 0;
        line-height: 1.5;
    }
    
    .divider-line {
        border-top: 2px solid #000;
        margin: 15px 0;
    }
    
    /* Report Header */
    .report-header {
        background-color: #4A7FC4;
        color: white;
        text-align: center;
        font-weight: bold;
        padding: 8px;
        font-size: 13px;
        letter-spacing: 1px;
        margin-bottom: 0;
    }
    
    /* Report Table */
    .report-table {
        margin-top: 0;
    }
    
    .report-table th {
        background-color: #f0f0f0;
        font-weight: bold;
        text-align: center;
        padding: 5px;
        font-size: 10px;
    }
    
    .report-table td {
        padding: 4px 6px;
        height: 20px;
    }
    
    .amount-col {
        text-align: left;
        padding-left: 8px;
    }
    
    /* Summary */
    .summary-table {
        margin-top: 0;
    }
    
    .summary-cell {
        background-color: #e8d7f1;
        padding: 5px 8px;
        font-size: 10px;
    }
    
    /* Certification */
    .cert-text {
        font-size: 9px;
        margin: 8px 0 12px;
        line-height: 1.3;
    }
    
    /* Signatures */
    .sig-table {
        border: 1.5px solid #000;
    }
    
    .sig-table td {
        border-right: 1.5px solid #000;
        border-left: none;
        border-top: none;
        border-bottom: 1.5px solid #000;
        padding: 8px;
        vertical-align: top;
        width: 33.33%;
    }
    
    .sig-table td:last-child {
        border-right: none;
    }
    
    .sig-title {
        font-size: 9px;
        margin-bottom: 30px;
    }
    
    .sig-space {
        height: 15px;
    }
    
    .sig-name {
        text-align: center;
        font-size: 9px;
        margin-bottom: 3px;
    }
    
    .sig-date {
        font-size: 9px;
    }
    
    @media print {
        .no-print {
            display: none !important;
        }
        
        .form-container {
            margin-bottom: 0;
            page-break-inside: avoid;
        }
    }
</style>
@endsection
