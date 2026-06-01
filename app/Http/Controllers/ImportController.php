<?php

namespace App\Http\Controllers;

use App\Models\Farmer;
use App\Models\WaterEntry;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\FarmersImport;
use App\Imports\WaterEntriesImport;

class ImportController extends Controller
{
    public function index()
    {
        return view('import.index');
    }

    public function farmers(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new FarmersImport(), $request->file('file'));
            return redirect()->route('import.index')->with('success', 'কৃষকদের তথ্য সফলভাবে ইমপোর্ট হয়েছে।');
        } catch (\Exception $e) {
            return redirect()->route('import.index')->with('error', 'ইমপোর্টে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function waterEntries(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        try {
            Excel::import(new WaterEntriesImport(), $request->file('file'));
            return redirect()->route('import.index')->with('success', 'পানি সরবরাহ এন্ট্রি সফলভাবে ইমপোর্ট হয়েছে।');
        } catch (\Exception $e) {
            return redirect()->route('import.index')->with('error', 'ইমপোর্টে সমস্যা হয়েছে: ' . $e->getMessage());
        }
    }

    public function template(string $type)
    {
        $headers = match ($type) {
            'farmers' => ['নাম', 'মোবাইল', 'গ্রাম', 'ইউনিয়ন', 'উপজেলা', 'জমির পরিমাণ', 'একক (acre/shotok/bigha)', 'জমির বিবরণ'],
            'water-entries' => ['কৃষক মোবাইল', 'তারিখ (YYYY-MM-DD)', 'ঘণ্টা', 'প্রতি ঘণ্টা রেট', 'মৌসুম', 'মন্তব্য'],
            default => [],
        };

        $filename = "template-{$type}.csv";
        $handle = fopen('php://output', 'w');

        ob_start();
        fputcsv($handle, $headers);
        fclose($handle);
        $content = ob_get_clean();

        return response($content, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }
}
