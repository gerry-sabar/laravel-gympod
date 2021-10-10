# The GymPod Backend

## Project setup
1. set .env with proper database connection
2. composer install
3. php artisan migrate
4. php artisan db:seed
5. php artisan passport:install

### Assumption
1. You can login from front-end or test at swagger by using credential: testing@testing.com/password or billy@evans.com/password (you can see this code at UserSeeder)
2. Dashboard will only load bookings from respected login user. For example if you login as billy@evans.com then it'll list all bookings made by billy@evans.com
3. uuid is used as id to get the detail but inside backend logic we'll use auto-increment id for fetching whenever possible, since uuid will take more resources and times in MySQL process which isn't optimal but at user level, using auto-increment id is not good since user can make a guess for any record although at logic code we also have validation user only able to access his/her own booking.
4. Laravel Resources and Requests (https://laravel.com/docs/8.x/eloquent-resources) is good to be implemented in this scenario, but given the time constrain I put everything inside controller which obviously in real-case it is not a good practice.
5. Login page isn't hard-coded although it's encouraged from the interview. But we have another important fundamental in developing microservices, which is JWT Token where access token and refresh token are crucial for security concern.

### Front-end integration
1. Makesure this app run by command php artisan serve using port 8000 since the base url for front end is using localhost:8000
2. API documentation is assumed we'll have postman for working among front-end & back-end engineers. In addition this app is also using swagger for API documentation at development phase
3. Swagger can be accessed at localhost:8000/api/documentation

### Design architecture
I'm using microservice approach instead of monolith with Laravel as the backend. Vue-js is placed as the front-end separate from Laravel. This way front-end is backend agnostic. Since we're using AWS, we can deploy back-end and front-end separatedly which backend is hosted in AWS beanstalk maybe. Then front-end can be served in AWS Cloud-front. Probably in the future we can mix backend not only depend on beanstalk & laravel stack. But probably using other language & framework such as Python with Flask/Django or even we use AWS lambda for serverless approach.

### Unit Test
I'd like to add unit testing at least with positive test scenario. However, given the circumstance I can't make on it for this interview purpose. But at this section I can explain as a developer why it a must and we can assume to provide PHP unit. We have to maintain the technical debt as low as possible but also weighing with other factors such as project time line. Sometimes we must sacrifice this one, but as soon as we got the time we should have this kind of test.

### CI/CD implementation
Since according to our previous discussion, I assume this project run on CI/CD with combination github & AWS. Therefore, when dealing with CI/CD we'll have .ebextensions folder inside it. Any commit will trigger to run AWS CodePipeline & AWS CodeDeploy. In this process will also trigger testing. If any new commit doesn't pass the test then the CI/CD process will be cancelled.
