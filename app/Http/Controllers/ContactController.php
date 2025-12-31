<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function submit(Request $request)
    {
        // Validate the form data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string|max:2000',
        ], [
            'name.required' => 'الاسم مطلوب',
            'phone.required' => 'رقم الهاتف مطلوب',
            'email.required' => 'البريد الإلكتروني مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'subject.required' => 'الموضوع مطلوب',
            'message.required' => 'الرسالة مطلوبة',
            'name.max' => 'الاسم يجب أن يكون أقل من 255 حرف',
            'email.max' => 'البريد الإلكتروني يجب أن يكون أقل من 255 حرف',
            'subject.max' => 'الموضوع يجب أن يكون أقل من 255 حرف',
            'message.max' => 'الرسالة يجب أن تكون أقل من 2000 حرف',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'يرجى تصحيح الأخطاء أدناه');
        }

        try {
            // Prepare email data
            $data = [
                'name' => $request->name,
                'phone' => $request->phone,
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ];

            // Send email to admin
            Mail::send('emails.contact', $data, function ($message) use ($data) {
                $message->to('info@e7lal.com')
                        ->subject('رسالة جديدة من موقع E7lal: ' . $data['subject'])
                        ->from($data['email'], $data['name'])
                        ->replyTo($data['email'], $data['name']);
            });

            // Also send confirmation email to user
            Mail::send('emails.contact-confirmation', $data, function ($message) use ($data) {
                $message->to($data['email'])
                        ->subject('تم استلام رسالتك - E7lal')
                        ->from('info@e7lal.com', 'E7lal.com');
            });

            return back()->with('success', 'تم إرسال رسالتك بنجاح! سنتواصل معك في أقرب وقت ممكن.');

        } catch (\Exception $e) {
            // Log the error
            \Log::error('Contact form error: ' . $e->getMessage());

            return back()
                ->withInput()
                ->with('error', 'حدث خطأ في إرسال الرسالة. يرجى المحاولة مرة أخرى أو التواصل معنا مباشرة.');
        }
    }
}