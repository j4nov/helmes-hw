# Frontend Setup (React Application)

This folder contains the frontend of the application, built with React.

## Requirements

- Node.js 16.0+ (LTS)
- npm

## Setup Instructions

### 1\. Install Dependencies

Run the following command to install frontend dependencies:

`npm install`

### 2\. Configure API URL

Make sure the frontend is connected to the correct backend API URL. Open the `.env` file and update the `VITE_API_URL` variable if its missing or wrong:

`VITE_API_URL=http://127.0.0.1:8000/api`

This will ensure the frontend communicates with the backend properly.

### 3\. Start the Development Server

To start the React development server, use:

`npm run dev`

This will open the application in your browser at `http://localhost:5173`.
