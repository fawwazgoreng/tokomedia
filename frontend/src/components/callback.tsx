
export const CallBacks = () => {
    const channell = new BroadcastChannel("my_channel");
    const CloseWIndow = async () => {
        channell.postMessage("refresh");
        await self.close();
    }
    CloseWIndow();
    return (
        <>
            <h1>Process</h1>
        </>
    );
}