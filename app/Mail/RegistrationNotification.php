<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegistrationNotification extends Mailable
{
    use Queueable, SerializesModels;
    protected $adminEmail;
    protected $otp;

    public function __construct($admin, $otp){
        $this->adminEmail = $admin;
        $this->otp = $otp;
    }

    public function build(){
        return 
        $this->from($this->adminEmail)
            ->view('livewire.emails.registration-notification')
            ->subject('NDC - HRIS Registration OTP')
            ->with([
                'header' => 'National Development Company',
                'greetings' => 'Good day!',
                'message_body' => '
                     <p class="message">
                        You have been granted access to the NDC - HRIS registration form. To proceed with your registration, 
                        please use the One-Time Password (OTP) provided below.
                    </p>
                    
                    <p class="message">
                        <strong>OTP:</strong> <span style="font-size: 18px; font-weight: bold;">' . $this->otp . '</span>
                    </p>
    
                    <p class="message">
                        This OTP is required to access the registration form. Please ensure that you use the email address 
                        that received this notification along with the OTP to proceed.
                    </p>
    
                    <div class="action-wrapper">
                        <a href="http://127.0.0.1:8000/register" target="_blank">
                            <button class="action-btn">
                                Register Now
                            </button>
                        </a>
                    </div>
    
                    <p class="message">
                        This OTP is valid for 24 hours. If you did not request this registration, please ignore this email. 
                        For assistance, feel free to contact us.
                    </p>
    
                    <p class="message">
                        Best regards, <br>
                        <strong style="font-size: 18px">National Development Company</strong> <br>
                        <i style="color: blue">info@ndc.gov.ph</i>
                    </p>',
                'footer' => '© 2025 National Development Company. All rights reserved.',
            ]);
    }

    public function envelope(): Envelope{
        $content = 'NDC - HRIS Registration OTP';
        return new Envelope(
            subject: $content,
        );
    }

    public function content(): Content{
        return new Content(
            view: 'livewire.emails.registration-notification',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array{
        return [];
    }
}
