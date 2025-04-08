import React, { useEffect, useState } from 'react';
import { getSectors, getUserData, saveUserData } from './api/api.ts';
import {Sector} from "./types/type.ts";
import emitter from "./emitter/eventEmitter.ts";
import LoadingSpinner from './components/LoadingSpinner.tsx';

export default function App() {
    const [sectors, setSectors] = useState<Sector[]>([]);
    const [filteredSectors, setFilteredSectors] = useState<Sector[]>([]);
    const [name, setName] = useState<string>('');
    const [selectedSectors, setSelectedSectors] = useState<number[]>([]);
    const [agreed, setAgreed] = useState<boolean>(false);
    const [message, setMessage] = useState<string>('');
    const [searchQuery, setSearchQuery] = useState<string>('');
    const [loading, setLoading] = useState(false);

    useEffect(() => {
        const startLoading = () => setLoading(true);
        const stopLoading = () => setLoading(false);

        emitter.on('startLoading', startLoading);
        emitter.on('stopLoading', stopLoading);

        return () => {
            emitter.off('startLoading', startLoading);
            emitter.off('stopLoading', stopLoading);
        };
    }, []);

    useEffect(() => {
        const fetchData = async () => {
            setLoading(true);
            try {
                const sectorsData = await getSectors();
                setSectors(sectorsData);
                setFilteredSectors(sectorsData);

                const userData = await getUserData();
                setName(userData.name);
                setSelectedSectors(userData.sectors ? userData.sectors.map((sector: Sector) => sector.id) : []);
                setAgreed(userData.agreed);
            } catch (error) {
                console.error('Error during data fetching:', error);
            } finally {
                setLoading(false);
            }
        };

        fetchData();
    }, []);

    const handleSearchChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const query = e.target.value.toLowerCase();
        setSearchQuery(query);

        const filtered = sectors.filter((sector) =>
            sector.label.toLowerCase().includes(query)
        );
        setFilteredSectors(filtered);
    };

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!name || selectedSectors.length === 0 || !agreed) {
            setMessage('All fields are required');
            return;
        }

        setLoading(true);

        try {
            const saveMessage = await saveUserData(name, selectedSectors, agreed);
            setMessage(saveMessage);

            const userData = await getUserData();
            setName(userData.name);
            setSelectedSectors(userData.sectors ? userData.sectors.map((sector: Sector) => sector.id) : []);
            setAgreed(userData.agreed);
        } catch (error) {
            console.error('Error during save:', error);
            setMessage('An error occurred while saving.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-400 to-slate-200 p-4">
            {loading && <LoadingSpinner />}
            <form
                onSubmit={handleSubmit}
                className="w-full max-w-md bg-slate-50 shadow-md rounded-lg p-8 space-y-6"
            >
                <h2 className="text-2xl font-bold text-slate-800">Sector Selection</h2>
                <p className="text-sm text-slate-600">
                    Please enter your name and pick the sectors you are currently involved in.
                </p>

                <div>
                    <label className="block text-sm font-medium text-slate-700 mb-1">Name</label>
                    <input
                        type="text"
                        value={name}
                        onChange={(e) => setName(e.target.value)}
                        className="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Your Name"
                        required
                    />
                </div>

                <div>
                    <label className="block text-sm font-medium text-slate-700 mb-1">Sectors</label>
                    <input
                        type="text"
                        placeholder="Search sectors..."
                        className="w-full mb-3 px-3 py-2 border border-slate-300 rounded focus:outline-none focus:ring-2 focus:ring-blue-400"
                        value={searchQuery}
                        onChange={handleSearchChange}
                    />
                    <div className="h-60 overflow-y-auto border border-slate-300 rounded px-3 py-2 bg-white">
                        {filteredSectors.map((sector) => (
                            <label key={sector.id} className="flex items-center space-x-2 mb-1">
                                <input
                                    type="checkbox"
                                    value={sector.id}
                                    checked={selectedSectors.includes(sector.id)}
                                    onChange={(e) => {
                                        const id = sector.id;
                                        setSelectedSectors((prev) =>
                                            e.target.checked
                                                ? [...prev, id]
                                                : prev.filter((i) => i !== id)
                                        );
                                    }}
                                    className="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                />
                                <span className="text-sm text-slate-700">{sector.label}</span>
                            </label>
                        ))}
                    </div>
                </div>

                <div className="flex items-center space-x-2">
                    <input
                        type="checkbox"
                        checked={agreed}
                        onChange={(e) => setAgreed(e.target.checked)}
                        className="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                        required
                    />
                    <label className="text-sm text-slate-700">Agree to terms</label>
                </div>

                <button
                    type="submit"
                    className="w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded-md hover:bg-blue-700 transition"
                >
                    Save
                </button>

                {message && (
                    <p className="text-sm text-center text-green-600 font-medium">{message}</p>
                )}
            </form>
        </div>
    );
}
