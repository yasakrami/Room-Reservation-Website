# University Room Reservation System

## Project Overview

The University Room Reservation System is a web application that facilitates room reservations and management for university users. It offers two user types: clients (students and faculty) and admins (administrative staff). Users can create accounts, log in, and perform various actions based on their roles.

### Login Page

- Users can log in with existing accounts or create new ones.
- Required information for account creation:
  - First and last name
  - Email address
  - Password
  - Account type (client/admin)

### Client Panel

- Logged-in client users access this panel.
- Logging out redirects users to the login page.
- Key pages/menus within the client panel include:

#### Profile Page

- Displays client profile information.

#### Rooms Page

- Lists all available rooms (not reservations).
- Allows searching/filtering rooms by date availability, projector availability, speaker system availability, etc.
- Enables marking rooms as favorites.
- Allows room reservation, including:
  - Reservation date and time (start and end times).
  - Reservation type (meeting/exam/classroom).
  - Number of attendees.
  - Reservation notes (special requests such as refreshments).
- Sends a confirmation email after reservation.
- Allows clients to provide feedback after using a room.

#### Reservations Page

- Displays both past and future reservations with detailed information.
- Allows modification/cancellation of future reservations.

#### Favorite Rooms Page

- Displays all favorited rooms.
- Allows room reservation directly from this page.

#### Messages Page

- Allows clients to send messages to admins regarding reservations.
- Displays incoming and outgoing messages to/from admins.
- Supports replying to incoming messages.
- Allows message deletion.

### Admin Panel

- Logged-in admin users access this panel.
- Logging out redirects users to the login page.
- Key pages/menus within the admin panel include:

#### Profile Page

- Displays admin profile information.

#### Rooms Page

- Lists all rooms (not reservations).
- Allows filtering rooms by utilities (e.g., projector, speaker system).
- Enables changing room status (available/unavailable).
- Allows adding new rooms with the following details:
  - Room number
  - Room floor
  - Room capacity
  - Room utilities (e.g., projector, microphone).

#### Reservations Page

- Lists all room reservations and their details.
- Supports modification/cancellation of room reservations due to issues.

#### Messages Page

- Allows admins to send messages to clients regarding their reservations.
- Displays incoming and outgoing messages to/from clients.
- Supports replying to incoming messages.
- Allows message deletion.

## Implementation

- Develop a web application using appropriate web technologies (e.g., HTML, CSS, JavaScript, a backend framework like Django, Ruby on Rails, or Express.js).
- Implement user authentication and session management for secure login/logout.
- Create database schemas to store user profiles, rooms, reservations, and messages.
- Develop interactive pages for profile management, room listing, reservation, feedback, and messaging.
- Implement filtering, searching, and sorting functionalities where needed.
- Set up email notifications for reservation confirmations.
- Ensure data validation and error handling.
- Deploy the application on a web server.
