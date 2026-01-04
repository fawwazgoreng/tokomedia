let email : string | null = null;

export const userOtp = {
    get : () => email,
    set : (payload : string) => {
        email = payload
    },
    clear : () => email = null
};