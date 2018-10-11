<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Session; 
use app\Http\Helpers\Helper;
use Illuminate\Support\Facades\Auth;
use ZipArchive;

class BackupController extends Controller
{
    /**
     * Show the application home page and link to create backup.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $view = array();
        return view('backup',$view);
    }
	/**
     * create backup of databases and download.
     *
     * @return database zip file
     */
	public function backupwithoutthirdparty(){
		/*Array of all databases need to create backup*/
		$database = array(
		'mysql1' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'your database host'),
            'port' => env('DB_PORT', 'your database port'),
            'database' => env('DB_DATABASE', 'your database name'),
            'username' => env('DB_USERNAME', 'your database username'),
            'password' => env('DB_PASSWORD', 'your databse password'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
		'mysql2' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'your database host'),
            'port' => env('DB_PORT', 'your database port'),
            'database' => env('DB_DATABASE', 'your database name'),
            'username' => env('DB_USERNAME', 'your database username'),
            'password' => env('DB_PASSWORD', 'your databse password'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
		'mysql3' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'your database host'),
            'port' => env('DB_PORT', 'your database port'),
            'database' => env('DB_DATABASE', 'your database name'),
            'username' => env('DB_USERNAME', 'your database username'),
            'password' => env('DB_PASSWORD', 'your databse password'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ],
		'mysql4' => [
            'driver' => 'mysql',
            'host' => env('DB_HOST', 'your database host'),
            'port' => env('DB_PORT', 'your database port'),
            'database' => env('DB_DATABASE', 'your database name'),
            'username' => env('DB_USERNAME', 'your database username'),
            'password' => env('DB_PASSWORD', 'your databse password'),
            'unix_socket' => env('DB_SOCKET', ''),
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'strict' => true,
            'engine' => null,
        ]);
		/* create directory under public where backups will be saved*/
		/* under public folder create backup and then create bcs folder and give permissions to read write */
		$public_dir=public_path().'/backup/bcs';
        $zipFileName = 'bcs'.date('Ymd-His').'.zip';
        $zip = new ZipArchive;
        if ($zip->open($public_dir . '/' . $zipFileName, ZipArchive::CREATE) === TRUE) {    
			foreach($database as $db=>$value){
				$filename = $db."backup-".date('Ymd-His').".sql";
				exec("mysqldump --user='".$value['username']."' --password='".$value['password']."' --host='".$value['host']."' --port='".$value['port']."' '".$value['database']."' > '/home/supergized/public_html/public/backup/bcs/".$filename."'");
				
				$zip->addFile(public_path('backup/bcs/').$filename,$filename);
				
			}
			$zip->close();
        }
        $filetopath=$public_dir.'/'.$zipFileName;
		
		if (file_exists($filetopath)) {
			return response()->download($filetopath);
			redirect('/test');
		} else {
			return ['status'=>'zip file does not exist'];
		}
	}
}
