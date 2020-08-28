<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class SendEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:send {user}';
// Optional argument...
//email:send {user?}
// Optional argument with default value...
//email:send {user=foo}


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send drip e-mails to a user';

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
     * @return int
     */
    public function handle()
    {
//        return 0;
//        User::find($this->argument('user'));
        echo "Send to " . $this->argument('user');
//        $this->options();
//        $this->arguments();


        // In addition to displaying output,
        // you may also ask the user to provide input during the execution of your command.
        // The ask method will prompt the user with the given question, accept their input,
        // and then return the user's input back to your command:
        $name = $this->ask("What is your name?");

        // The secret method is similar to ask,
        // but the user's input will not be visible to them as they type in the console.
        // This method is useful when asking for sensitive information such as a password:
        $password = $this->secret("What is the password?");

        // If you need to ask the user for a simple confirmation, you may use the confirm method.
        // By default, this method will return false.
        // However, if the user enters y or yes in response to the prompt, the method will return true.
        if ($this->confirm("Do you wish to continue?")) {
            //
        }


        // The anticipate method can be used to provide auto-completion for possible choices.
        // The user can still choose any answer, regardless of the auto-completion hints:
        $name = $this->anticipate('What is your name?', ['Taylor', 'Dayle']);

        // Alternatively, you may pass a Closure as the second argument to the anticipate method.
        // The Closure will be called each time the user types an input character.
        // The Closure should accept a string parameter containing the user's input so far,
        // and return an array of options for auto-completion:
        $name = $this->anticipate('What is your name?', function ($input) {
            // Return auto-completion options...
        });

        // If you need to give the user a predefined set of choices,
        // you may use the choice method.
        // You may set the array index of the default value to be returned if no option is chosen:
        $defaultIndex = 0;
        $name = $this->choice('What is your name?', ['Taylor', 'Dayle'], $defaultIndex);


        // In addition, the choice method accepts optional fourth and
        // fifth arguments for determining the maximum number of attempts to
        // select a valid response and whether multiple selections are permitted:

        $name = $this->choice(
            'What is your name?',
            ['Taylor', 'Dayle'],
            $defaultIndex,
            $maxAttempts = null,
            $allowMultipleSelections = false
        );


        $this->info('Display this on the screen with green color');

        $this->error('Something went wrong!');//red color

        $this->line('Display this on the screen with plain, uncolored console output');


        //Table outputs
        $headers = ['Name', 'Email'];
        $users = User::all(['name', 'email'])->toArray();
        //The table method makes it easy to correctly format multiple rows / columns of data.
        // Just pass in the headers and rows to the method.
        // The width and height will be dynamically calculated based on the given data:
        $this->table($headers, $users);


        // For long running tasks, it could be helpful to show a progress indicator.
        // Using the output object, we can start, advance and stop the Progress Bar.
        // First, define the total number of steps the process will iterate through.
        // Then, advance the Progress Bar after processing each item:

        $users = User::all();
        $bar = $this->output->createProgressBar(count($users));

        $bar->start();

        foreach ($users as $user) {
            $this->info($user->name);
            $bar->advance();
        }
        $bar->finish();


        // Sometimes you may wish to call other commands from an existing Artisan command.
        // You may do so using the call method.
        // This call method accepts the command name and an array of command parameters:
        $this->call('email:retrieve', [
            'user' => 1, '--queue' => 'default'
        ]);


        // If you would like to call another console command and suppress all of its output,
        // you may use the callSilent method.
        // The callSilent method has the same signature as the call method:
        $this->callSilent('email:retrieve', [
            'user' => 1, '--queue' => 'default'
        ]);

    }
}
