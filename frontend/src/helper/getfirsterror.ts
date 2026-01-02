export const getFirstError = <T extends Record<string , string[]>>(error : T) => {
    const firstError = Object.keys(error)[0];
    if (firstError && firstError[0]) {
        return firstError[0];
    }
    return firstError;
}