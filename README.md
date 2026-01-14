# Image Market place
Within this application it is possible for accounts to upload images and share them to users users by selling them to each other.
This application has been made by Wessel B for Inholland University of Applied Sciences as an assignment. Different aspects of the application such as a custom router will be described below.

## Login credentials for test accounts
<ul>
  <li>
    Username: admin, Password: admin (the role of this account is Admin)
  </li>
  <li>
    Username: user, Password: user (the role of this account is User)
  </li>
</ul>

## Instructions for starting the application

## Automatic view mapping
When rendering a view, the application will automatically determine which view file to render and what data to pass to it. First it determines which view to render by getting the name of the called controller and the called method. Assuming the render method gets called From a PortfolioController from method index, it will render the view /Portfolio/index.php. If there is a directory passed to the render method, it will choose that view file instead, but that is optional. After the view file has been located, it will pass data to that view such as error messages, success messages and ViewData. All of these are optional of course. The render method can be found in the base controller, so if a PortfolioController wants to render a view then it will first need to inherit the base controller class and it will need call $this->displayView() with the necessary parameters.

#### Files
<ul>
  <li>
      app\src\Controllers\Controller.php (The base controller file)
  </li>
</ul>

## Automated router
Upon making a request, the router will make sure that the right controller method gets called. To ensure that the router knows which method to call, then each controller method needs to have a route attribute implemented. The need of having a big list of controllers hardcoded which each route has been AUTOMATED. If you want to link the route url /portfolio to the controller PortfolioController and the method index, then you need to add the attribute #[Route("GET", "/portfolio")] on top of the index method. If you want to add request parameters to the method then you can add them like this: #[Route("GET", "/portfolio", ["id"])] and add a array $requestParams parameter to the index method. Route /portfolio/5 will now call the index method of the portfolio controller and will pass the value 5 to $requestParams["id"]. Below you can find the file containing the source code of the custom made router.

#### Files
<ul>
  <li>
      app\src\Framework\Router.php (The main router file)
  </li>
</ul>

## Dependency injection
It is no longer neccessary to create objects such as services and repositories in this application. All dependencies can now be injected into the constructor. This issue has been solved by the use of the software module PHP-DI. This software module can be installed by calling "composer require php-di/php-di" in your CLI. If a controller requires an IImagesService then all the controller needs to do is inject the object into the constructor like this: public function __construct(IImagesService $imagesService). All dependency bindings can be found in the main dependencies file, see below.

#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>

## WCAG 2.2
This application is compliant with the WCAG 2.2 guidelines. Below you can find a list of requirements that have been fulfilled.

#### Semantic tags
One of the reasons why the application is WCAG 2.2 compliant is due to the use of semantic HTML tags. For example the register page uses the following symantic HTML tags: nav, form, main, header, footer and h3.

#### Labels and inputs
Input and label elements are also used correctly. This is how they are implemented on the register page: \<label for="repeat_password">Repeat password</label> \<input type="password" id="repeat_password">. As you can see, both for and id have the same values, which cause them to be connected to each other.

#### Empty links or buttons
All links also have a destination which prevents the empty link problem.

#### Descriptive alt texts for images
All images have descriptive in alt text in them. This is an example of how it has been implemented in the images displayer partial: \<img src="/assets/img/UserUploadedImages/1.png" alt="Alt image text example">. This makes it possible to see the content of the image even if they fail to load. It can also make screenreaders have the ability to read images for people with eyesight issues.

#### Text resizing
If the user decides to zoom in at 200% percent then the layout of the application will adapt. One example of adaptation is that the navbar wil turn into a hamburger menu if the screen width becomes too small.

#### Contrast ratio
Finally, the application's contrast between text and backgrounds has been carefully implemented. For example, the contrast score between the color of enabled navbar button (#E5E5E5) and its text color (#000000) is 16.67.

#### Files
<ul>
  <li>
      app\src\Views\Authentication\register.php (Register page)
  </li>
  <li>
      app\src\Views\Partials\navbarHeader.php (Navbar Partial)
  </li>
  <li>
      app\src\Views\Partials\imagesDisplay.php (Images displayer partial)
  </li>
</ul>

## GDPR
This application is also compliant with the GDPR guidelines. Below you can find a list of requirements that have been fulfilled.

#### Account deletion
One of the examples why it is GDPR compliant is due to the implementation of account deletion. When a user wants to delete their acccount, then they can make contact with an admin and then the admin will make sure to delete their account. An example of how the application deletes an user is by calling the deleteUser method from the UsersService: $this->usersService->deleteUserByUserId($userId);. This code can be found in the UsersApiController file.

#### Storing sensitive data securely
Upon creating an account or updating an existing account, the application will make sure that passwords will be stored as hashed strings in the database. This is an example of how passwords get turned into hashes within the application: password_hash($rawPassword, PASSWORD_DEFAULT);. The hashing algorithm can be found in the UserService file.

#### Only collecting needed data
The application asks as little data as possible from the user. The only data that the application collects is username and password of the user. You can find an example of this at the register page.

#### Preventing GET request data leaks
The application also makes sure to include POST methods on all form elements. As an example this is how it is implemented in the register page: \<form action="/register" method="POST" id="registerForm">. By making each form have a POST method, it prevents the data leaks theat GET requests can cause.

#### Privacy statement page
Finally, the application also includes privacy statement page. This privacy statement page can be accessed by entering the following url when running the application: /privacy.

#### Files
<ul>
  <li>
      app\src\Views\Authentication\register.php (An example of a form element using a POST method and minimizing personal information)
  </li>
  <li>
      app\src\Controllers\ApiControllers\UsersApiController.php (The controller containing the user deletion method)
  </li>
  <li>
      app\src\Services\UsersService.php (The users service which contains the password hashing algorithm)
  </li>
  <li>
      app\src\Views\Privacy\index.php (Privacy statement page)
  </li>
</ul>


