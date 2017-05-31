<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
class AutoMailCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'mail:auto';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto mail.';

    protected $sendAddress;

    protected $sendName;



    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->subject = $this->input->getOption('subject');
        $this->sendAddress = $this->input->getOption('send_address');
        $this->sendName = $this->input->getOption('send_name');

        if( $this->input->getOption('welcome') ) {
            $this->welcome();
        }
        if( $this->input->getOption('approval') ) {
            $this->approval();
        }
        if( $this->input->getOption('feedback') ) {
            $this->feedback();
        }
        if( $this->input->getOption('waitactive') ) {
            $this->waitactive();
        }
        if( $this->input->getOption('newsletter') ) {
            $this->newsletter();
        }

    }

    private function welcome()
    {
        $userId = $this->input->getOption('user_id');
        $user = User::find($userId);
        if (!$user) {
            return $this->error('Missing user_id field.');
        }
        $user->active = 1;
        $user->save();
        $user = $user->toArray();
        unset($user['password'],
            $user['created_by'],
            $user['updated_by'],
            $user['company_id'],
            $user['jt_id']);

        $arrData = [
                'user'  => $user,
                'token' => Crypt::encrypt($user['email'])
            ];

        $subject = $this->subject;
        if (!$subject) {
            $subject = 'Welcome to AnvyDigital.';
        }
        $sendAddress = $this->sendAddress;
        $sendName = $this->sendName;
        if (!$subject) {
            $subject = 'Welcome!';
        }

        $signupNotificationEmails = Configure::getSignupNotificationEmails();
        $arrEmailsCC = explode(",", $signupNotificationEmails);
        $arrEmailsCC = array_map('trim',$arrEmailsCC);

        Mail::send('emails.auth.welcome', $arrData, function($message) use($sendAddress, $sendName, $subject, $arrEmailsCC) {
            $message->to($sendAddress, $sendName)->bcc($arrEmailsCC)->subject($subject);
        });

        $this->info('A welcome email was sent to '.$sendAddress.'.');
    }

    private function waitactive()
    {
        $userId = $this->input->getOption('user_id');
        $user = User::find($userId);
        if (!$user) {
            return $this->error('Missing user_id field.');
        }
        $user = $user->toArray();
        unset($user['password'],
            $user['created_by'],
            $user['updated_by'],
            $user['company_id'],
            $user['jt_id']);
        $user['token'] = str_rot13(base64_encode($user['email'].'--'.$user['id']));
        $arrData = ['user' => $user];

        $subject = $this->subject;

        if (!$subject) {
            $subject = '[ANVYDIGITAL]You have been registered.';
        }

        // $signupNotificationEmails = Configure::getSignupNotificationEmails();
        // $arrEmailsCC = explode(",", $signupNotificationEmails);
        // $arrEmailsCC = array_map('trim',$arrEmailsCC);

        Mail::send('emails.auth.waitactivate', $arrData, function($message) use($user, $subject) {
            $message->to($user['email'])->subject($subject);
        });
        $this->info('A email was sent to '.$user['email'].'.');
    }

    private function approval()
    {
        $sendAddress = Configure::getAnvyMail();
        $validator = Validator::make(
            array('email' => $sendAddress),
            array('email' => 'required|email')
        );

        if ($validator->fails()) {
            return $this->error($validator->messages()->all());
        }

        $userId = $this->input->getOption('user_id');
        $user = User::find($userId);
        if (!$user) {
            return $this->error('Missing user_id field.');
        }
        $user = $user->toArray();
        unset($user['password'],
            $user['created_by'],
            $user['updated_by'],
            $user['company_id'],
            $user['jt_id']);

        $arrData = ['user' => $user];

        $subject = $this->subject;

        if (!$subject) {
            $subject = '[ANVYDIGITAL][APPROVAL]New member has been registered. Please take a look.';
        }
        $token = Crypt::encrypt($user['email']);
        $arrData['token'] = $token;
        DB::table('approved_code')
            ->insert([
                'type'  => 'user',
                'email' => $user['email'],
                'token' =>  $token,
                'created_at' => date('Y-m-d H:i:s')
            ]);
        Mail::send('emails.auth.approval', $arrData, function($message) use($sendAddress, $subject) {
            $message->to($sendAddress)->subject($subject);
        });

        $this->info('A approval email was sent to '.$sendAddress.'.');
    }

    private function feedback()
    {
        // $sendAddress = Configure::getAnvyMail();
        $sendAddress = $this->input->getOption('send_address');
        $validator = Validator::make(
            array('email' => $sendAddress),
            array('email' => 'required|email')
        );

        if ($validator->fails()) {
            return $this->error($validator->messages()->all());
        }

        $feedBackId = $this->input->getOption('feedback_id');
        $feedback = Contact::find($feedBackId);
        if (!$feedback) {
            return $this->error('Missing feedback_id field.');
        }
        $feedback = $feedback->toArray();

        $arrData = ['feedback' => $feedback];

        $subject = $this->subject;

        if (!$subject) {
            $subject = 'Message from Anvy Website regarding '. $feedback['subject'];
        }
        Mail::send('emails.feedback', $arrData, function($message) use($feedback, $sendAddress, $subject) {
            $message->replyTo($feedback['email'], $feedback['first_name'].' '.$feedback['last_name'])
                    ->to($sendAddress)
                    ->subject($subject);
        });
        $this->info('A feedback email was sent.');
    }

    private function newsletter()
    {
        $email = $this->input->getOption('send_address');
        $name = $this->input->getOption('send_name');
        $unsubscribe = $this->input->getOption('unsubscribe');
        $arrData = ['email' => $email, 'name' => $name, 'unsubscribe' => $unsubscribe];

        if($unsubscribe)
            $subject = '[Visual Impact]Unsubscribed newsletter.';
        else
            $subject = '[Visual Impact]There\'s someone just subscribed for email newsletter.';

        $signupNewsletterEmails = Configure::getSignupNewsletterEmails();
        $arrEmailsNotification = explode(",", $signupNewsletterEmails);
        $arrEmailsNotification = array_map('trim',$arrEmailsNotification);

        Mail::send('emails.auth.newsletter', $arrData, function($message) use($subject, $arrEmailsNotification) {
            $message->to($arrEmailsNotification)->subject($subject);
        });
    }    

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            array('send_address', null, InputOption::VALUE_OPTIONAL, 'Mail address for sending.', null),
            array('send_name', null, InputOption::VALUE_OPTIONAL, 'Name of sending contact.', null),
            array('subject', null, InputOption::VALUE_OPTIONAL, 'Email subject.', null),
            array('user_id', null, InputOption::VALUE_OPTIONAL, 'Id of user using for fetching info.', null),
            array('feedback_id', null, InputOption::VALUE_OPTIONAL, 'Id of feedback record.', null),
            array('unsubscribe', null, InputOption::VALUE_OPTIONAL, 'Unsubscribe newsletter.', null),
            array('approval', null, InputOption::VALUE_NONE, 'Sending approval mail to Anvy Staffs.', null),
            array('waitactive', null, InputOption::VALUE_NONE, 'Sending waiting active account email.', null),
            array('welcome', null, InputOption::VALUE_NONE, 'Sending welcome mail.', null),
            array('feedback', null, InputOption::VALUE_NONE, 'Sending feedback mail.', null),
            array('newsletter', null, InputOption::VALUE_NONE, 'Sending newsletter notification email.', null),
        );
    }
}