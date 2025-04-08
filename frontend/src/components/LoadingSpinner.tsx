export default function LoadingSpinner() {
    return (
        <div className='fixed left-0 top-0 z-50 flex h-full w-full items-center justify-center bg-white opacity-80'>
            <div className='relative h-16 w-16'>
                <div className='absolute h-full w-full animate-spin rounded-full border-4 border-blue-700 border-t-transparent'></div>
            </div>
        </div>
    );
}