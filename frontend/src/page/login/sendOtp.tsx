import { useState, type FormEvent } from "react";
import api from "@/helper/axios";
import { useNavigate } from "react-router-dom";
import { userOtp } from "@/helper/auth/userotp";

export default function SendOtp() {
    const [loading, setLoading] = useState<boolean>(false);
    const [otp, setOtp] = useState<string>('');
    const navi = useNavigate();
    const [status, setStatus] = useState<string | undefined | string[]>('');
    const HandleLogin = async (e: FormEvent) => {
        e.preventDefault();
        try {
            setLoading(true);
            if (otp.length < 3) {
                setStatus('username harus lebih dari 3 karakter');
                return;
            }
            const email = userOtp.get();
            const payload = {
                otp,
                email
            }
            const res = await api.post("/api/otp/verification" , payload);
            if (res.status !== 200) {
                setStatus(res.data.message)
                return ;
            }
            setStatus("berhasil register");
            navi("/")
        } catch {
            setStatus('coba lagi nanti')
        } finally {
            setLoading(false);
        }
    }

    return (
        <>
            <main className=" h-screen flex flex-col justify-center">
                <div className="container bg-white rounded-2xl shadow-2xl min-w-xl max-w-3xl flex mx-auto py-10">
                    <div className="flex flex-col mx-auto text-2xl ">
                        <h1 className="text-center text-blue-500 font-bold text-4xl mt-4 ">Tokomedia</h1>
                        {status ?
                            <p className={"my-3 max-w-96 w-auto min-w-80 text-red-800"}>{status}</p>
                            : <p className="my-3 w-full"></p>}
                        <form action="" onSubmit={(e) => HandleLogin(e)} className="flex flex-col gap-3">
                            <div className="relative z-0 mb-5 group h-10 max-w-96 w-auto min-w-80">
                                <input id="otp" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type="text" disabled={loading} onChange={(e) => setOtp(e.target.value)} placeholder="" />
                                <label htmlFor="otp" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">otp</label>
                            </div>
                            <button type="submit" className=" bg-blue-600 rounded-2xl py-2 font-bold text-white " disabled={loading}>{loading ? 'loading' : 'send'}</button>
                        </form>
                    </div>
                </div>
            </main>
        </>
    );
}