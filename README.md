# This project was developed using PHP 7.3, Apache2 and MySql 5.7

 * For running this project is necessary to put the file <br> mosyle-challeng in the /var/www directory from apache 2 and then running docker-compose up -d inside the file
##Issues

If an error ocurred on running the project, there are some tips here:
 * cd /etc/apache2/mods-available
 * a2enmod rewrite
 * Alter the conf file from Apache: /etc/apache2/apache2.conf
        <br> Alter the line: <Directory /var/www/>
   Options Indexes FollowSymLinks
   AllowOverride None # <---- AllowOverride All
 * Restart apache2: /etc/init.d/apache2 restart

## Endpoints

    The URL for requests is configured on insominia file in this project:
    
    http://localhost/mosyle-challenge/public/api/
    
    POST /users/ (create a new user)
    
    POST /login (authenticate with a user)

    GET /users/:iduser (get a user)

    GET /users/ (get the list of users)
    
    PUT /users/:iduser (edit the user)

    DELETE /users/:iduser (delete the user)

    POST /users/:iduser/drink (increment the count of how many times you drank coffee)

    GET /users/:iduser/drinks (lists a user's record history per day)
    
    POST /users/drinks (lists the ranking of the user who drank more coffee on a determined day)
    

