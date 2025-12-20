<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Symfony\Component\Console\Output\BufferedOutput;
use Throwable;

class AdminDatabaseController extends Controller
{
    public function index()
    {
        return view('admin.database.index');
    }

    public function runQuery(Request $request)
    {
        $request->validate([
            'query' => 'required|string|max:5000',
        ]);

        $query = trim($request->input('query'));
        $results = [];
        $error = null;
        $executionTime = 0;

        try {
            $startTime = microtime(true);

            // Check if it's a SELECT query
            $queryType = strtoupper(substr($query, 0, 6));

            if ($queryType === 'SELECT') {
                $results = DB::select($query);
                $results = collect($results)->toArray();
            } else {
                // Execute non-SELECT queries
                $affectedRows = DB::statement($query);
                $results = ['affected_rows' => $affectedRows, 'message' => 'Query executed successfully'];
            }

            $executionTime = round((microtime(true) - $startTime) * 1000, 2); // in milliseconds

        } catch (Throwable $e) {
            $error = $e->getMessage();
        }

        return response()->json([
            'success' => $error === null,
            'results' => $results,
            'error' => $error,
            'execution_time' => $executionTime,
            'query_type' => $queryType
        ]);
    }

    public function runCommand(Request $request)
    {
        $request->validate([
            'command' => 'required|string|max:1000',
        ]);

        $command = $request->input('command');
        $output = new BufferedOutput();
        $exitCode = 0;
        $executionTime = 0;

        try {
            $startTime = microtime(true);

            // Parse command and arguments
            $parts = explode(' ', $command);
            $commandName = array_shift($parts);

            // Execute artisan command
            $exitCode = Artisan::call($commandName, $parts, $output);

            $executionTime = round((microtime(true) - $startTime) * 1000, 2);

        } catch (Throwable $e) {
            $output->writeln('Error: ' . $e->getMessage());
            $exitCode = 1;
        }

        return response()->json([
            'success' => $exitCode === 0,
            'output' => $output->fetch(),
            'exit_code' => $exitCode,
            'execution_time' => $executionTime,
            'command' => $command
        ]);
    }

    public function getTables()
    {
        try {
            $tables = DB::select("SHOW TABLES");
            $tableNames = [];

            // Get table names from the result
            $databaseName = DB::getDatabaseName();
            foreach ($tables as $table) {
                $tableNames[] = $table->{'Tables_in_' . $databaseName};
            }

            return response()->json([
                'success' => true,
                'tables' => $tableNames
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function getTableStructure(Request $request)
    {
        $request->validate([
            'table' => 'required|string|max:255',
        ]);

        try {
            $table = $request->input('table');
            $columns = DB::select("DESCRIBE {$table}");
            $indexes = DB::select("SHOW INDEX FROM {$table}");

            return response()->json([
                'success' => true,
                'columns' => $columns,
                'indexes' => $indexes
            ]);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
    }
}

