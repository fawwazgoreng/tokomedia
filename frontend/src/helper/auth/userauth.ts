let accesstoken : string | null = null;

export const userStore = {
    get : () => accesstoken,
    set : (token : string) => {
        accesstoken = token
    },
    clear : () => {accesstoken = null;}
};