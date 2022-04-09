### Project set up

Run the following commands to set up the project:

    git clone 
    cd project_name
    cp .env.example .env
    composer install
    npm install
    ./venodr/bin/sail up
    sail artisan key:generate
    sail artisan migrate
    sail artisan queue:work
