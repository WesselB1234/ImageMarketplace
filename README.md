# Image Market place
Within this application it is possible for accounts to upload images and share them to users by selling them to each other.
This application has been made by Wessel B for Inholland University of Applied Sciences as an assignment. Different aspects of the application such as a custom-made router will be described below.

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
<ol>
  <li>
    Open the Docker desktop app.
  </li>
  <li>
    Run "docker compose up" in your CLI that is with the directory of the in the main project.
  </li>
  <li>
    Enter "http://localhost/login" in your address bar.
  </li>
  <li>
      Enter username and password of your account. The account credentials can be found above in the account credentials section.
  </li>
  <li>
      You should now be on the Portfolio page. You can now upload images and sell images to other users. Keep in mind that not all functionalities are available if your account has a "User" role.
  </li>
</ol>

## Automatic view mapping
When displaying a view, the application will automatically determine which view to display and what data to pass to it. 

First it determines which view to file to display as a view by getting the name of the controller and name the method that the router has called. For example, if the displayView() method gets called by a PortfolioController from method index, it will display the view /Portfolio/index.php. If is a directory passed to the displayView() method, it will choose that view file instead, but that is optional. 

After the view file has been located, it will display the view and pass the following data to the view: error message, success message and ViewData. The displayView() method can be found in the base controller, so if a PortfolioController wants to display a view, then it will need to inherit the base controller class first. After inheriting, the PortfolioController can call $this->displayView().

<img width="1011" height="791" alt="Schermafbeelding 2026-01-15 123151" src="https://github.com/user-attachments/assets/383ab823-a4cd-4233-9dfc-1aed9838ed3f" />

#### Files
<ul>
  <li>
      app\src\Controllers\Controller.php (The base controller file)
  </li>
</ul>

## Automated router
Upon making a request, the router will make sure that the right controller method gets called. If a controller method wants to be binded to a route URL, then the method needs to have a route attribute implemented. The need of having a big list of hardcoded controllers with routes has been AUTOMATED. 

For example, if PortfolioController wants method index binded route /portfolio, then it needs to add the attribute #[Route("GET", "/portfolio")] on top of the method. If the route also needs request parameters, then they can be added like this: #[Route("GET", "/portfolio", ["id"])]. In the parameters section of the index method, there should also be an array $requestParams parameter like this: index(array $requestParams). Route /portfolio/5 will now call the index method of the portfolioController and will pass the value of 5 to $requestParams["id"]. Below you can find the file containing the source code of the custom-made router.

<img width="633" height="505" alt="Schermafbeelding 2026-01-15 121400" src="https://github.com/user-attachments/assets/516a728c-33fe-4b1e-97bc-7818805b8152" />
<img width="1255" height="308" alt="Schermafbeelding 2026-01-15 121323" src="https://github.com/user-attachments/assets/c46ed2e8-23ea-435a-8f11-f4e3b2e59004" />

#### Files
<ul>
  <li>
      app\src\Framework\Router.php (The main router file)
  </li>
</ul>

## Dependency injection
It is no longer necessary to create objects such as services and repositories in this application. All dependencies can now be injected into the constructor. This issue has been solved using the software module PHP-DI. This software module can be installed by calling "composer require php-di/php-di" in the CLI. 

If a controller requires an IImagesService then all the controller needs to do is inject the object into the constructor like this: public function __construct(IImagesService $imagesService). Be aware that all dependencies that include interfaces need to be wired. All dependency wirings can be found in the main dependencies file.

<img width="1079" height="721" alt="Schermafbeelding 2026-01-15 121121" src="https://github.com/user-attachments/assets/b8a77a15-1b32-4105-ae72-b0e8acc4e7e3" />
<img width="630" height="42" alt="Schermafbeelding 2026-01-15 121304" src="https://github.com/user-attachments/assets/33412c71-ce2e-47ae-9b8e-4bd42a99ba4e" />

#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>

## WCAG 2.2
This application is compliant with the WCAG 2.2 guidelines. Below you can find a list of requirements that have been fulfilled.

#### Semantic tags
One of the reasons why the application is WCAG 2.2 compliant is due to the use of semantic HTML tags. For example, the register page uses the following semantic HTML tags: nav, form, main, header, footer, section and h3.

#### Labels and inputs
Input and label elements are also used correctly. This is how they are implemented on the register page: \<label for="repeat_password">Repeat password</label> \<input type="password" id="repeat_password">. As you can see, both for and id have the same values, which cause them to be connected to each other.

<img width="1159" height="122" alt="Schermafbeelding 2026-01-15 120429" src="https://github.com/user-attachments/assets/7766e4ac-3bce-4cc8-a2af-b02ae2cf78c4" />

#### Empty links or buttons
All links also have a destination which prevents the empty link problem.

#### Descriptive alt texts for images
All images have descriptive in alt text in them. This is an example of how it has been implemented in the image’s displayer partial: \<img src="/assets/img/UserUploadedImages/1.png" alt="Alt image text example">. This makes it possible to see the content of the image even if they fail to load. It can also make screen readers can read images for people with eyesight issues.

#### Text resizing
If the user decides to zoom in at 200% percent, then the layout of the application will adapt. One example of adaptation is that the navbar will turn into a hamburger menu if the screen width becomes too small.

<img width="908" height="479" alt="Schermafbeelding 2026-01-15 122709" src="https://github.com/user-attachments/assets/c7699cc4-990b-4d2b-8560-a0c60a0d8a33" />

#### Contrast ratio
Finally, the application's contrast between text and backgrounds has been carefully implemented. For example, the contrast score between the color of enabled navbar button (#E5E5E5) and its text color (#000000) is 16.67.

<img width="737" height="528" alt="Schermafbeelding 2026-01-15 120353" src="https://github.com/user-attachments/assets/550c1ad7-7411-4692-98ed-9e697c3fb83a" />

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
One of the examples why it is GDPR compliant is due to the implementation of account deletion. When a user wants to delete their account, then they can contact an admin and then the admin will make sure to delete their account. An example of how the application deletes a user is by calling the deleteUser() method from the UsersService: $this->usersService->deleteUserByUserId($userId);. This code can be found in the UsersApiController file.

#### Storing sensitive data securely
Upon creating an account or updating an existing account, the application will make sure that passwords will be stored as hashed strings in the database. This is an example of how passwords get turned into hashes within the application: password_hash($rawPassword, PASSWORD_DEFAULT);. The hashing algorithm can be found in the UserService file.

#### Only collecting needed data
The application asks as little data as possible from the user. The only data that the application collects is username and password of the user. You can find an example of this at the register page.

<img width="722" height="676" alt="Schermafbeelding 2026-01-15 120917" src="https://github.com/user-attachments/assets/9cb84b3c-fc31-4cf0-a83d-e0e33b9f39c8" />

#### Preventing GET request data leaks
The application also makes sure to include POST methods on all form elements. As an example, this is how it is implemented in the register page: \<form action="/register" method="POST" id="registerForm">. By making each form have a POST method, it prevents the data leaks that GET requests can cause.

#### Privacy statement page
Finally, the application also includes privacy statement page. This privacy statement page can be accessed by entering the following URL when running the application: /privacy.

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
