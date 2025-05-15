<?php

namespace App\Models;

use App\Mail\UserMail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PDF;

class ApiModel extends Model
{
    use HasFactory;
    public $slugLength;

    #--------------------------------------------------------#
    #                                                        #
    # Function : slug                                        #
    # Model Used: This is Common model                       #
    # Action : Generate Slug                                 #
    # Returns : String with given length                     #
    # Return Type : string                                   #
    # Return To:  MailQueue Model                            #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function __construct()
    {
        $this->slugLength = config('constants.SLUG_LENGTH');
    }

    public function slug($digit = 0)
    {
        $digit = $digit != 0 ? $digit : (empty($this->slugLength) ? 12 : $this->slugLength);
        $slug = substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, $digit);
        return $slug;
    }

    #--------------------------------------------------------#
    # Function : getKeyBySlug                                #
    # Model Used: No model used.work with DB facade          #
    # Action : key from table by slug                        #
    # Returns : value of given key by slug                   #
    # Return Type : int/string                               #
    # Return To: respected controller                        #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function getKeyBySlug($tableName = '', $key = '', $slug = '', $id = '', $column = '', $statusId = true)
    {
        if (!empty($slug) || (!empty($id) && !empty($column))) {
            $qry = DB::table($tableName);
            if ($statusId) {
                $qry->where('status_id', '<>', config('constants.STATUS_DELETE'));
            }
            if (!empty($slug)) {
                $qry->where('slug', $slug);
            }
            if (!empty($id)) {
                if (!empty($column)) {
                    $qry->where($column, $id);
                }
            }
            $getKey = $qry->select($key)->first();
            $getId = null;
            if (isset($getKey) && !empty($getKey)) {
                $getId = $getKey->$key;
            }
            return $getId;
        } else {
            return 0;
        }
    }

    #--------------------------------------------------------#
    # Function : totalOfColumn                               #
    # Model Used: This is Common model                       #
    # Action : Generate total of column                      #
    # Returns : total of given column                        #
    # Return Type : string                                   #
    # Return To:  respected Model                            #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function totalOfColumn($array, $columnName)
    {
        $total = array_column($array, $columnName);
        return number_format(array_sum($total), 2, '.', '');
    }

    public function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }

    /* Upload File */
    public function uploadFile($request, $fieldName, $path, $fileName = '')
    {
        try {
            $image = $request->file($fieldName);
            $ext = $image->extension();
            $timeStamp = Carbon::now()->timestamp;
            if ($fileName != '') {
                $fileName = $this->clean($fileName) . $timeStamp . '.' . $ext;
            } else {
                $fileName = rand('11111', '99999') . $timeStamp . '.' . $ext;
            }
            $result = $image->storeAs($path, $fileName);
            if (!empty($result)) {
                return successResponse(Response::HTTP_OK, Lang::get('messages.UPLOAD_SUCCESS'), [
                    'fileName' => $fileName,
                    'filePath' => Storage::url($path . $fileName),
                ]);
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.UPLOAD_ERROR'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    #--------------------------------------------------------#
    # Function : getIdBySlugAndRefTableId                    #
    # Model Used: Facade                                     #
    # Action : get id by ref table id and slug               #
    # Returns : id                                           #
    # Return Type : int  s                                   #
    # Return To:  where This Call                            #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function getIdBySlugAndRefTableId($tableId = '', $slug = "")
    {
        $tableRef = $this->tableRef();
        $getId = null;
        if (array_key_exists($tableId, $tableRef)) {
            $tableName = $tableRef[$tableId];
            $pKey = $this->getTablePrimaryOrUniqueKey($tableName, "primary");
            $tableData = DB::table($tableName)->where('status_id', '<>', config('constants.STATUS_DELETE'))
                ->where('slug', $slug)->first();
            if (isset($tableData) && !empty($tableData)) {
                $getId = $tableData->$pKey;
            }
            return $getId;
        } else {
            return $getId;
        }
    }
    /* Devloper : Gaurangkumar Patel */
    public function tableRef()
    {
        $result = DB::table('sys_ref_table')->pluck('ref_table', 'ref_table_id')->toArray();
        return $result;
    }

    /* Devloper : Gaurangkumar Patel */
    public function getTablePrimaryOrUniqueKey($table, $key = 'PRIMARY')
    {
        //get the array of table indexes
        $result = DB::select(DB::raw("SHOW KEYS FROM {$table} WHERE Key_name = '$key'"));
        return !empty($result[0]->Column_name) ? $result[0]->Column_name : "";
    }

    #--------------------------------------------------------#
    # Function : decode                                      #
    # Returns : return decode value                          #
    # Developer : Mehul Chaudhari                            #
    #--------------------------------------------------------#
    public function decode($value)
    {
        return urldecode(stripslashes($value));
    }

    #--------------------------------------------------------#
    # Function : getCurrentDate                              #
    # Returns : return current date                          #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function getCurrentDate()
    {
        return DB::select("select current_date() as curr_date")[0]->curr_date;
    }

    #--------------------------------------------------------#
    # Function : getCurrentDate                              #
    # Returns : return current day                           #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function getCurrentDays()
    {
        return DB::select("select to_days(current_date()) as curr_days")[0]->curr_days;
    }

    #--------------------------------------------------------#
    # Function : getIntervalDate                             #
    # Returns : return interval date,D                       #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#
    public function getIntervalDate($date, $interval = 6, $type = "MONTH")
    {
        if ($type == "MONTH") {
            return Carbon::parse($date)->addMonths($interval);
        } else {
            return Carbon::parse($date)->addDays($interval);
        }
    }

    #--------------------------------------------------------#
    #                                                        #
    # Function : sendMail                                    #
    # Model Used: MailQueue                                  #
    # Action : MailQueues Entry                              #
    # Returns : Array response message                       #
    # Return Type : Array                                    #
    # Return To:  where This Call                            #
    # Developer : Ravi Tewatia                              #
    #--------------------------------------------------------#

    public function sendMail($mailQueue = [])
    {
        try {
            $mailQueueResult = MailQueue::insert($mailQueue);
            if ($mailQueueResult) {
                return successResponse(Response::HTTP_CREATED, Lang::get('messages.MAIL_DATA_INSERT'));
            } else {
                return errorResponse(Response::HTTP_BAD_REQUEST, Lang::get('messages.MAIL_DATA_NOT_INSERT'));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    public function sendInstantMail($email, $details, $module, $lang, $view = "mails.UserMail")
    {
        try {
            Mail::to($email)
                ->send(new UserMail($details));
            $messageEmail = view($view)->with(["details" => $details])->render();
            $mailQue = [
                "to_email" => $email,
                "subject" => $details['subject'],
                "message" => $messageEmail,
                "module" => Str::upper($module),
            ];
            if (Mail::failures()) {
                $mailQue['mail_send'] = 5;
                MailQueue::insert($mailQue);
                return errorResponse(Response::HTTP_UNPROCESSABLE_ENTITY, Lang::get("$lang.mail_error"));
            } else {
                $mailQue['mail_send'] = 2;
                MailQueue::insert($mailQue);
                return successResponse(Response::HTTP_OK, Lang::get("$lang.mail_success"));
            }
        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

    // Generate PDF
    public function createPDF($fileName, $filePath, $viewName, $tableData, $htmlView = "")
    {
        // share data to view
        view()->share('employee', $data);
        $pdf = PDF::load(view('pdf.demo-pdf-export', ['result' => $tableData]));
        return $pdf->download('pdf_file.pdf');
    }
    public function generatePdfReport($viewName, $filePath, $rFileName, $tblResult, $printView = 'portrait', $paperSize = 'a4')
    {
        #common code will be here 0666 permission code loadHTML
        try {
            if (!Storage::exists($filePath)) {
                $file = Storage::makeDirectory($filePath, 0777, true);
            }
            return PDF::loadHTML(view($viewName)->with(["result" => $tblResult])->render())
                ->setPaper($paperSize, $printView)
                ->setWarnings(false)
                ->save(Storage::path($filePath . $rFileName));

        } catch (\Exception $ex) {
            $result = [
                'line' => $ex->getLine(),
                'file' => $ex->getFile(),
            ];
            return catchResponse(Response::HTTP_INTERNAL_SERVER_ERROR, $ex->getMessage(), $result);
        }
    }

}
