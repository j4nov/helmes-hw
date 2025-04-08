# Sector submission task

This project consists of two separate parts: the **backend** and **frontend**. Each part has its own setup instructions. Follow the instructions for both parts to get the project up and running.

### Backend Setup

The backend is built with Symfony and handles all the API logic. To set up the backend, follow the instructions in the `backend/README.md` file.

### Frontend Setup

The frontend is built with React. To set up the frontend, follow the instructions in the `frontend/README.md` file.

---

### Notes:
- After setting up the backend, make sure to run migrations and fill the `sector` table with fixtures.
- The frontend and backend are designed to run independently, but they work together to form a full-stack application.

### Database Schema

```sql
TABLE sector (
    id SERIAL PRIMARY KEY,
    label VARCHAR(128) NOT NULL
);

TABLE user_submission (
    id SERIAL PRIMARY KEY,
    name VARCHAR(128) NOT NULL,
    agreed BOOLEAN NOT NULL,
    session_id VARCHAR(255) NULL
);

TABLE user_submission_sector (
    id SERIAL PRIMARY KEY,
    user_submission_id INT NOT NULL,
    sector_id INT NOT NULL,
    FOREIGN KEY (user_submission_id) REFERENCES user_submission(id) ON DELETE CASCADE,
    FOREIGN KEY (sector_id) REFERENCES sector(id) ON DELETE CASCADE
);
```


