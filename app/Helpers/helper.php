<?php

use App\Models\Settings;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;

if (!function_exists('generateReference')) { /* Check_for "generateReference" */
    function generateReference()
    {
        $reference = (string) Str::uuid();
        $reference = str_replace('-', '', $reference);

        return $reference;
    }
} /* End_check for "generateReference" */

if (!function_exists('isApiRequest')) { /* Check_for "isApiRequest" */
    function isApiRequest()
    {
        if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
            return true;
        }

        return false;
    }
} /* End_check for "isApiRequest" */


if (!function_exists('durationUnit')) {
    function durationUnit()
    {
        return [
            'days',
            'weeks',
            'months',
            'years',
            'lifetime'
        ];
    }
}

if (!function_exists('convertDaysToUnit')) {
    function convertDaysToUnit(int $days, $unit)
    {
        $result = 0;

        switch ($unit) {
            case 'weeks':
                $result = floor($days / 7);
                break;
            case 'months':
                $result = round(abs(Carbon::now()->addDays($days)->diffInDays(Carbon::now()) / 30));
                break;
            case 'years':
                $futureDate = Carbon::now()->addDays($days);
                $result = round(abs(Carbon::now()->diffInYears($futureDate)));
                break;
            default:
                $result = $days;
        }

        return $result;
    }
}


if (!function_exists('serviceDownMessage')) {
    function serviceDownMessage()
    {
        return 'Unable to complete your request the moment.';
    }
}

if (!function_exists('uploadFile')) { /* send to log" */
    function uploadFile($file, $folder, $driver = "")
    {
        // using config
        if (config('app.env') === 'local') {
            // The environment is local
            $file_name = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path("{$folder}"), $file_name);

            $fileUrl = url("{$folder}/" . $file_name);
        } else {
            if ($driver === "do_spaces") {
                $extension = $file->getClientOriginalExtension(); // Get the file extension (e.g., 'jpg', 'png', 'pdf')
                // Generate a unique filename using a timestamp and a random string
                $uniqueFileName = time() . '_' . uniqid() . '.' . $extension;

                $filePath = "{$folder}/" . $uniqueFileName;

                $path = Storage::disk('do_spaces')->put($filePath, $file, 'public');
                $fileUrl = Storage::disk('do_spaces')->url($path);
            } else {
                $file_name = time() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("{$folder}"), $file_name);

                $fileUrl = url("{$folder}/" . $file_name);
            }
        }

        return $fileUrl;
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile($fileUrl)
    {
        // Extract file path from URL
        $filePath = parse_url($fileUrl, PHP_URL_PATH);

        if (config('app.env') === 'local') {
            $path = public_path($filePath);
            if (file_exists($path)) {
                unlink($path);
            }
        } else {
            if (Storage::disk('do_spaces')->exists($filePath)) {
                Storage::disk('do_spaces')->delete($filePath);
            }
        }
    }
}


if (!function_exists('validdateFile')) {
    function validdateFile($image, $ext, $allowedExtension)
    {
        if (($image->getSize() / 1000000) > 2) {
            throw ValidationException::withMessages(['attachments' => "Maximum 5 images can be uploaded"]);
        }
        if (!in_array($ext, $allowedExtension)) {
            throw ValidationException::withMessages(['attachments' => "Only png, jpg, jpeg, pdf images are allowed"]);
        }
    }
}


if (!function_exists('generateRandomPassword')) {
    function generateRandomPassword($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $password = '';
        $characterCount = strlen($characters);

        for ($i = 0; $i < $length; ++$i) {
            $index = mt_rand(0, $characterCount - 1);
            $password .= $characters[$index];
        }

        return $password;
    }
}

if (!function_exists('isApiRequest')) { /* Check_for "isApiRequest" */
    function isApiRequest()
    {
        if (request()->wantsJson() || str_starts_with(request()->path(), 'api')) {
            return true;
        }

        return false;
    }
} /* End_check for "isApiRequest" */


/**
 * Send mail with the specified driver
 *
 * @param string  $driver
 * @param string  $email
 * @param array  $data
 *
 * @return bool  true|false
 */
if (!function_exists('sendMailByDriver')) { /* Check_for "sendMailByDriver" */
    function sendMailByDriver($driver, $email, $data)
    {
        // Mailgun drivers
        $mailgunDrivers = ['mailgun'];

        // Criteria to lookout for in the selected mail driver
        $criteria = in_array($driver, $mailgunDrivers) ? config('mail.mailers.' . $driver . '.domain') : config('mail.mailers.' . $driver . '.username');

        // Verify if the driver exist in the env and mail configuration file
        if (!is_null($criteria)) {
            // Try and send the mail via the selected dirver
            try {
                Mail::mailer($driver)->to($email)->send($data);

                return true;
            } catch (\Exception $e) {
                sendToLog($driver . ' Failure => ' . $e->getMessage());
                // Log the driver mail error
                logger($driver == 'smtp' ? 'Mailtrap' : 'Mailgun' . ' Failure => ', [
                    'message' => $e->getMessage(),
                ]);

                return false;
            }
        }

        logger(ucfirst($driver) . ' driver configuration is empty.');
        return false;
    }
}


if (!function_exists('sendToLog')) { /* send to log" */
    function sendToLog($error)
    {
        logger($error);
        // Log the exception if it's in a local environment
        // if (env('APP_ENV') === 'local') {
        //     logger($error);
        // } else {
        //     try {
        //         // $logFilesPath = storage_path('logs');
        //         // // // get all log files
        //         // $logFiles = File::glob($logFilesPath . '/*.log');
        //         // // // get latest log
        //         // $latestLogFile = array_pop($logFiles);

        //         // $logFileContent = File::get($latestLogFile);

        //         $payload = [
        //             'text' => $error
        //         ];

        //         $client = new \GuzzleHttp\Client();
        //         $client->post('https://hooks.slack.com/services/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', [
        //             'headers' => [
        //                 'Content-Type' => 'application/json',
        //             ],
        //             'json' => $payload,
        //         ]);
        //     } catch (\Throwable $th) {
        //         throw $th;
        //     }
        // }
    }
}


if (!function_exists('limitWords')) {
    function limitWords($string, $limit = 100)
    {
        $words = explode(' ', $string);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . '...';
        }
        return $string;
    }
}

if (!function_exists('systemSettings')) {
    function systemSettings()
    {
        return Settings::first();
    }
}
