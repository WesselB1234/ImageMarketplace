# Image Marketplace
Within this application, accounts can upload images and sell them to other users.
This application was made by Wessel B. for Inholland University of Applied Sciences as an assignment. Different aspects of the application such as a custom-made router will be described below.

## Login credentials for test accounts
<ul>
  <li>
    Username: admin, Password: admin (this account has the "Admin" role)
  </li>
  <li>
    Username: user, Password: user (this account has the "User" role)
  </li>
</ul>

## Instructions for starting the application
<ol>
  <li>
      Create an .env file in the /frontend and /backend directores. Use the .envExamples that are implemented in /backend and /frontend directories as a reference for implementing both .env files.
  </li>
  <li>
      In your terminal: "cd frontend", "npm install" and "npm run dev"
  </li>
  <li>
      Open the Docker desktop app.
  </li>
  <li>
      In your terminal: "cd backend" and "docker compose up"
  </li>
  <li>
      Enter "http://localhost:5173/auth/login" into your address bar.
  </li>
  <li>
      Enter the username and password of your account. The account credentials can be found above in the account credentials section.
  </li>
  <li> 
      Press the "Login" button.
  </li>
  <li>
      You will now be redirected to the Portfolio page. You can now upload images and sell them to other users. Keep in mind that not all functionalities are available if your account has a "User" role.
  </li>
</ol>

## Pagination
<img width="772" height="191" alt="Schermafbeelding 2026-06-16 111507" src="https://github.com/user-attachments/assets/c38915fd-9e78-41ce-a47f-c79919e9efdd" />

<img width="938" height="437" alt="Schermafbeelding 2026-06-16 111529" src="https://github.com/user-attachments/assets/8dad801c-4b62-4de8-8e7d-7114d19874dc" />
#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>

## Atomic component structure
<img width="377" height="508" alt="Schermafbeelding 2026-06-16 111229" src="https://github.com/user-attachments/assets/d2183b78-4819-4f81-874a-73ddb4bf2903" />

## Pinia statemanagement
<img width="937" height="858" alt="Schermafbeelding 2026-06-16 111625" src="https://github.com/user-attachments/assets/2c1078a7-9505-4abc-9f3c-3871ad9bed98" />

#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>

## JWT authentication

<img width="1092" height="837" alt="Schermafbeelding 2026-06-16 112736" src="https://github.com/user-attachments/assets/7feb2901-62e4-4cba-a20e-599ed39b9810" />

<img width="1285" height="806" alt="Schermafbeelding 2026-06-16 112916" src="https://github.com/user-attachments/assets/e554ada3-269b-4e65-9787-c2d137a04cfc" />

<img width="670" height="863" alt="Schermafbeelding 2026-06-16 113150" src="https://github.com/user-attachments/assets/41758830-a93c-4c3e-9d9b-ea17fb1ebce7" />

#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>

## Authorization with and without roles

If user is not logged in then decoding will fail and GetLoggedInUser will throw an exception. If it succeeds it will check if the user's role is authorized to access the endpoint.

<img width="995" height="476" alt="Schermafbeelding 2026-06-16 111305" src="https://github.com/user-attachments/assets/baa5b79d-6807-489d-89a2-99979390f419" />

<img width="846" height="312" alt="Schermafbeelding 2026-06-16 111319" src="https://github.com/user-attachments/assets/2b4ef109-f31e-403b-8df0-bd64eeb9ea10" />

<img width="905" height="131" alt="Schermafbeelding 2026-06-16 111358" src="https://github.com/user-attachments/assets/82317e35-dacd-46eb-9d0a-7d2c608637e5" />

<img width="551" height="121" alt="Schermafbeelding 2026-06-16 111431" src="https://github.com/user-attachments/assets/b95b7598-5aa9-4f85-ae9f-5157191cb876" />

#### Files
<ul>
  <li>
      app\src\Framework\Dependencies.php (The file containing all dependency bindings)
  </li>
</ul>
