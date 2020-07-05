RUN

1.  in app dir           -> docker-compose up -d
2.  in app dir           ->docker-compose exec app bash
3.  in app container     -> composer require laravel/ui
4.  in app container     ->  php artisan ui vue --auth

5.  in local project dir -> npm install    
6.  in local project dir -> npm run dev
7.  in local project dir -> npm run watch

8.  in app container     -> php artisan make:test EventTest --unit
9.  in app container     -> php artisan test
10. in app container     -> php artisan migrate
11. in app container     -> php artisan tinker
12. in app container     -> factory(App\Event::class, 10)->create();
13. in app container     -> factory(App\Participant::class, 20)->create();
14. in app container     -> php artisan queue:table
15. in app container     -> php artisan migrate
16. in app container     -> php artisan make:job SendMail
17. in app container     -> php artisan queue:work --queue=high,default
 


