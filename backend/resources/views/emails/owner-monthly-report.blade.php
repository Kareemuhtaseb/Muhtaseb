<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Monthly Property Report - {{ $month }}</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            font-size: 11px; 
            line-height: 1.4;
            color: #333;
        }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 20px;
        }
        .header h1 { 
            color: #2c3e50; 
            margin: 0; 
            font-size: 24px;
        }
        .header h2 { 
            color: #7f8c8d; 
            margin: 5px 0; 
            font-size: 18px;
        }
        .header h3 { 
            color: #34495e; 
            margin: 5px 0; 
            font-size: 16px;
        }
        .summary-box { 
            border: 1px solid #bdc3c7; 
            padding: 15px; 
            margin: 15px 0; 
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .financial-summary { 
            background: linear-gradient(135deg, #3498db, #2980b9); 
            color: white; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 8px;
        }
        .financial-summary h3 { 
            margin-top: 0; 
            color: white;
        }
        .financial-summary table { 
            width: 100%; 
            border-collapse: collapse;
        }
        .financial-summary td { 
            padding: 8px; 
            border-bottom: 1px solid rgba(255,255,255,0.2);
        }
        .property-table { 
            width: 100%; 
            border-collapse: collapse; 
            margin: 20px 0; 
            font-size: 10px;
        }
        .property-table th, .property-table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        .property-table th { 
            background-color: #34495e; 
            color: white; 
            font-weight: bold;
        }
        .property-table tr:nth-child(even) { 
            background-color: #f8f9fa; 
        }
        .positive { 
            color: #27ae60; 
            font-weight: bold;
        }
        .negative { 
            color: #e74c3c; 
            font-weight: bold;
        }
        .section { 
            margin: 25px 0; 
            page-break-inside: avoid;
        }
        .section h3 { 
            color: #2c3e50; 
            border-bottom: 2px solid #3498db; 
            padding-bottom: 5px;
        }
        .category-breakdown { 
            margin: 15px 0; 
        }
        .category-item { 
            display: flex; 
            justify-content: space-between; 
            padding: 5px 0; 
            border-bottom: 1px solid #eee;
        }
        .maintenance-status { 
            display: inline-block; 
            padding: 2px 8px; 
            border-radius: 3px; 
            font-size: 9px; 
            font-weight: bold;
        }
        .status-open { background-color: #f39c12; color: white; }
        .status-in-progress { background-color: #3498db; color: white; }
        .status-completed { background-color: #27ae60; color: white; }
        .status-cancelled { background-color: #e74c3c; color: white; }
        .page-break { page-break-before: always; }
        .footer { 
            margin-top: 30px; 
            text-align: center; 
            font-size: 10px; 
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📊 Monthly Property Investment Report</h1>
        <h2>{{ $month }}</h2>
        <h3>{{ $summary['owner']->name }}</h3>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
    
    <!-- Executive Summary -->
    <div class="section">
        <h3>📊 Executive Summary</h3>
        <div class="financial-summary">
            <h3>Financial Overview</h3>
            <table>
                <tr>
                    <td><strong>Total Portfolio Income:</strong></td>
                    <td class="positive">${{ number_format($summary['total_income'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Total Portfolio Expenses:</strong></td>
                    <td class="negative">${{ number_format($summary['total_expenses'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Net Portfolio Income:</strong></td>
                    <td class="{{ $summary['net_income'] >= 0 ? 'positive' : 'negative' }}">${{ number_format($summary['net_income'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Your Share ({{ $summary['owner']->share_percentage }}%):</strong></td>
                    <td class="positive">${{ number_format($summary['owner_share'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Distributions Received:</strong></td>
                    <td class="positive">${{ number_format($summary['distributions'], 2) }}</td>
                </tr>
                <tr>
                    <td><strong>Balance Owed:</strong></td>
                    <td class="{{ $summary['balance_owed'] >= 0 ? 'positive' : 'negative' }}">${{ number_format($summary['balance_owed'], 2) }}</td>
                </tr>
            </table>
        </div>
        
        <div class="summary-box">
            <h4>Portfolio Overview</h4>
            <div class="category-item">
                <span>Total Properties:</span>
                <span><strong>{{ count($summary['properties']) }}</strong></span>
            </div>
            <div class="category-item">
                <span>Total Units:</span>
                <span><strong>{{ $summary['occupancy_summary']['total_units'] }}</strong></span>
            </div>
            <div class="category-item">
                <span>Occupied Units:</span>
                <span><strong>{{ $summary['occupancy_summary']['occupied_units'] }}</strong></span>
            </div>
            <div class="category-item">
                <span>Overall Occupancy Rate:</span>
                <span><strong>{{ $summary['occupancy_summary']['overall_occupancy_rate'] }}%</strong></span>
            </div>
            <div class="category-item">
                <span>Maintenance Requests:</span>
                <span><strong>{{ $summary['maintenance_summary']['total_requests'] }}</strong></span>
            </div>
        </div>
    </div>
    
    <!-- Property Details -->
    <div class="section">
        <h3>🏢 Property Performance Details</h3>
        <table class="property-table">
            <thead>
                <tr>
                    <th>Property</th>
                    <th>Ownership %</th>
                    <th>Income</th>
                    <th>Expenses</th>
                    <th>Net Income</th>
                    <th>Your Share</th>
                    <th>Units</th>
                    <th>Occupancy</th>
                    <th>ROI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['properties'] as $propertyData)
                <tr>
                    <td><strong>{{ $propertyData['property']->name }}</strong><br>
                        <small>{{ $propertyData['property']->location }}</small></td>
                    <td>{{ $propertyData['ownership_percentage'] }}%</td>
                    <td class="positive">${{ number_format($propertyData['income'], 2) }}</td>
                    <td class="negative">${{ number_format($propertyData['expenses'], 2) }}</td>
                    <td class="{{ $propertyData['net_income'] >= 0 ? 'positive' : 'negative' }}">${{ number_format($propertyData['net_income'], 2) }}</td>
                    <td class="positive">${{ number_format($propertyData['owner_share'], 2) }}</td>
                    <td>{{ $propertyData['occupied_units'] }}/{{ $propertyData['total_units'] }}</td>
                    <td>{{ $propertyData['occupancy_rate'] }}%</td>
                    <td>{{ $propertyData['total_units'] > 0 ? round(($propertyData['net_income'] / $propertyData['total_units']), 2) : 0 }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Income Breakdown -->
    <div class="section">
        <h3>💰 Income Breakdown by Category</h3>
        <div class="category-breakdown">
            @foreach($summary['income_by_category'] as $category => $data)
            <div class="category-item">
                <span>{{ $category }} ({{ $data['count'] }} transactions)</span>
                <span class="positive">${{ number_format($data['total'], 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Expense Breakdown -->
    <div class="section">
        <h3>💸 Expense Breakdown by Category</h3>
        <div class="category-breakdown">
            @foreach($summary['expenses_by_category'] as $category => $data)
            <div class="category-item">
                <span>{{ $category }} ({{ $data['count'] }} transactions)</span>
                <span class="negative">${{ number_format($data['total'], 2) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    
    <!-- Maintenance Summary -->
    <div class="section">
        <h3>🔧 Maintenance Summary</h3>
        <div class="summary-box">
            <h4>Request Status Breakdown</h4>
            @foreach($summary['maintenance_summary']['status_breakdown'] as $status => $count)
            @if($count > 0)
            <div class="category-item">
                <span>{{ ucfirst(str_replace('_', ' ', $status)) }}</span>
                <span><strong>{{ $count }}</strong></span>
            </div>
            @endif
            @endforeach
        </div>
        
        <div class="summary-box">
            <h4>Priority Breakdown</h4>
            @foreach($summary['maintenance_summary']['priority_breakdown'] as $priority => $count)
            @if($count > 0)
            <div class="category-item">
                <span>{{ ucfirst($priority) }} Priority</span>
                <span><strong>{{ $count }}</strong></span>
            </div>
            @endif
            @endforeach
        </div>
    </div>
    
    <!-- Distribution Details -->
    @if($summary['distribution_details']->count() > 0)
    <div class="section">
        <h3>💳 Distribution Details</h3>
        <table class="property-table">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Amount</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summary['distribution_details'] as $distribution)
                <tr>
                    <td>{{ $distribution->date->format('M j, Y') }}</td>
                    <td class="positive">${{ number_format($distribution->amount, 2) }}</td>
                    <td>{{ $distribution->notes ?? 'N/A' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    
    <!-- Detailed Property Reports -->
    @foreach($summary['properties'] as $index => $propertyData)
    <div class="section {{ $index > 0 ? 'page-break' : '' }}">
        <h3>📋 Detailed Report: {{ $propertyData['property']->name }}</h3>
        
        <div class="summary-box">
            <h4>Property Overview</h4>
            <div class="category-item">
                <span>Location:</span>
                <span>{{ $propertyData['property']->location }}</span>
            </div>
            <div class="category-item">
                <span>Your Ownership:</span>
                <span>{{ $propertyData['ownership_percentage'] }}%</span>
            </div>
            <div class="category-item">
                <span>Total Units:</span>
                <span>{{ $propertyData['total_units'] }}</span>
            </div>
            <div class="category-item">
                <span>Occupied Units:</span>
                <span>{{ $propertyData['occupied_units'] }}</span>
            </div>
            <div class="category-item">
                <span>Vacant Units:</span>
                <span>{{ $propertyData['vacant_units'] }}</span>
            </div>
            <div class="category-item">
                <span>Maintenance Units:</span>
                <span>{{ $propertyData['maintenance_units'] }}</span>
            </div>
            <div class="category-item">
                <span>Occupancy Rate:</span>
                <span>{{ $propertyData['occupancy_rate'] }}%</span>
            </div>
        </div>
        
        <!-- Property Income Details -->
        @if($propertyData['income_details']->count() > 0)
        <div class="summary-box">
            <h4>Income Transactions</h4>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Payee</th>
                        <th>Memo</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propertyData['income_details'] as $income)
                    <tr>
                        <td>{{ $income->date->format('M j, Y') }}</td>
                        <td>{{ $income->category->name ?? 'N/A' }}</td>
                        <td>{{ $income->payee }}</td>
                        <td>{{ $income->memo }}</td>
                        <td class="positive">${{ number_format($income->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Property Expense Details -->
        @if($propertyData['expense_details']->count() > 0)
        <div class="summary-box">
            <h4>Expense Transactions</h4>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Category</th>
                        <th>Payee</th>
                        <th>Memo</th>
                        <th>Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propertyData['expense_details'] as $expense)
                    <tr>
                        <td>{{ $expense->date->format('M j, Y') }}</td>
                        <td>{{ $expense->category->name ?? 'N/A' }}</td>
                        <td>{{ $expense->payee }}</td>
                        <td>{{ $expense->memo }}</td>
                        <td class="negative">${{ number_format($expense->amount, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
        
        <!-- Property Maintenance Details -->
        @if($propertyData['maintenance_requests']->count() > 0)
        <div class="summary-box">
            <h4>Maintenance Requests</h4>
            <table class="property-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Unit</th>
                        <th>Tenant</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Priority</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($propertyData['maintenance_requests'] as $request)
                    <tr>
                        <td>{{ $request->created_at->format('M j, Y') }}</td>
                        <td>{{ $request->unit->unit_number ?? 'N/A' }}</td>
                        <td>{{ $request->tenant->name ?? 'N/A' }}</td>
                        <td>{{ Str::limit($request->description, 50) }}</td>
                        <td><span class="maintenance-status status-{{ $request->status }}">{{ ucfirst($request->status) }}</span></td>
                        <td>{{ ucfirst($request->priority) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
    @endforeach
    
    <div class="footer">
        <p>This report was automatically generated by the Property Management System</p>
        <p>For questions or concerns, please contact your property manager</p>
    </div>
</body>
</html>
