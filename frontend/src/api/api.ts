import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL;

export const getSectors = async () => {
    try {
        const response = await axios.get(`${API_URL}/sectors`);
        return response.data;
    } catch (error) {
        console.error("Error fetching sectors:", error);
        throw error;
    }
};

export const getUserData = async () => {
    try {
        const response = await axios.get(`${API_URL}/me`, { withCredentials: true });
        return response.data;
    } catch (error) {
        console.error("Error fetching user data:", error);
        throw error;
    }
};

export const saveUserData = async (name: string, sectors: number[], agreed: boolean) => {
    try {
        const response = await axios.post(
            `${API_URL}/save`,
            { name, sectors, agreed },
            { withCredentials: true }
        );
        return response.status === 200 ? 'Saved successfully!' : 'Failed to save data.';
    } catch (error) {
        console.error("Error saving user data:", error);
        throw new Error("An error occurred while saving.");
    }
};
