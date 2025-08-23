<?php

namespace App\Services;

use App\Models\CommercialComplex;
use App\Models\Shop;
use App\Models\Company;
use App\Models\Contract;
use App\Models\MonthlyIncome;
use App\Models\IncomeTransaction;
use App\Models\ComplexExpense;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ExcelImportService
{
    public function importFromExcel(string $filePath): array
    {
        $results = [
            'commercial_complex' => null,
            'shops' => 0,
            'companies' => 0,
            'contracts' => 0,
            'monthly_incomes' => 0,
            'income_transactions' => 0,
            'expenses' => 0,
        ];

        try {
            DB::beginTransaction();

            // Create commercial complex
            $complex = CommercialComplex::create([
                'name' => 'Mojama Commercial Complex',
                'description' => 'Commercial complex imported from Excel',
                'address' => 'Mojama, Saudi Arabia',
            ]);
            $results['commercial_complex'] = $complex->id;

            // Import shops from "2024 Income" sheet
            $this->importShops($complex->id, $filePath);
            $results['shops'] = Shop::where('commercial_complex_id', $complex->id)->count();

            // Import contracts from "Contracts" sheet
            $this->importContracts($complex->id, $filePath);
            $results['contracts'] = Contract::whereHas('shop', function ($query) use ($complex) {
                $query->where('commercial_complex_id', $complex->id);
            })->count();

            // Import income transactions from "Eyad Income" sheet
            $this->importIncomeTransactions($complex->id, $filePath);
            $results['income_transactions'] = IncomeTransaction::whereHas('contract.shop', function ($query) use ($complex) {
                $query->where('commercial_complex_id', $complex->id);
            })->count();

            // Count companies
            $results['companies'] = Company::count();

            DB::commit();
            return $results;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function importShops(int $complexId, string $filePath): void
    {
        $shopsData = $this->readExcelSheet($filePath, '2024 Income');
        
        foreach ($shopsData as $row) {
            if (empty($row['Income 2024']) || $row['Income 2024'] === 'Shop Number') {
                continue;
            }

            $shopNumber = $row['Income 2024'];
            $shopName = $row['Unnamed: 1'] ?? '';

            if (!empty($shopNumber) && !empty($shopName)) {
                Shop::updateOrCreate(
                    ['commercial_complex_id' => $complexId, 'shop_number' => $shopNumber],
                    [
                        'shop_name' => $shopName,
                        'status' => 'occupied',
                    ]
                );
            }
        }
    }

    private function importContracts(int $complexId, string $filePath): void
    {
        $contractsData = $this->readExcelSheet($filePath, 'Contracts');
        
        foreach ($contractsData as $row) {
            if (empty($row['Shop Number'])) {
                continue;
            }

            $shopNumber = $row['Shop Number'];
            $shop = Shop::where('commercial_complex_id', $complexId)
                       ->where('shop_number', $shopNumber)
                       ->first();

            if (!$shop) {
                continue;
            }

            // Create or find company (using shop name as company name for now)
            $company = Company::firstOrCreate(
                ['name' => $shop->shop_name],
                [
                    'business_type' => 'Commercial',
                    'contact_person' => 'Contact Person',
                ]
            );

            // Create contract
            Contract::create([
                'shop_id' => $shop->id,
                'company_id' => $company->id,
                'start_date' => Carbon::parse($row['Start']),
                'end_date' => Carbon::parse($row['End']),
                'yearly_rent' => $row['Yearly Rent'],
                'yearly_rent_with_tax' => $row['Yearly Rent + Tax'],
                'yearly_services' => $row['Yearly Services'],
                'status' => $row['Period'] == 1 ? 'active' : 'expired',
            ]);
        }
    }

    private function importIncomeTransactions(int $complexId, string $filePath): void
    {
        $incomeData = $this->readExcelSheet($filePath, 'Eyad Income ');
        
        foreach ($incomeData as $row) {
            if (empty($row['Amount']) || empty($row['Date'])) {
                continue;
            }

            // Find the active contract for this transaction
            $transactionDate = Carbon::parse($row['Date']);
            $activeContract = Contract::whereHas('shop', function ($query) use ($complexId) {
                $query->where('commercial_complex_id', $complexId);
            })
            ->where('status', 'active')
            ->where('start_date', '<=', $transactionDate)
            ->where('end_date', '>=', $transactionDate)
            ->first();

            if ($activeContract) {
                IncomeTransaction::create([
                    'contract_id' => $activeContract->id,
                    'amount' => $row['Amount'],
                    'transaction_date' => $transactionDate,
                    'description' => $row['Description'] ?? 'Monthly Rent',
                    'type' => 'rent',
                    'payment_method' => 'cash',
                ]);
            }
        }
    }

    private function readExcelSheet(string $filePath, string $sheetName): array
    {
        // This is a simplified version - you'll need to implement actual Excel reading
        // For now, we'll return an empty array
        return [];
    }
}
