import { useState, type FormEvent } from "react";
import { useAppDispatch, useAppSelector } from "@/helper/storefn";
import { login, profile } from "@/store/user";
import { FaEye, FaEyeSlash } from "react-icons/fa";
import { useParams } from "react-router-dom";
import { BsGoogle } from "react-icons/bs";

export default function LoginPage() {
    const param = useParams();
    console.log(param);
    const handleGoogleLogin = () => {
        const width = window.innerWidth;
        console.log(width);
        if (width > 768) {
            const url = `${import.meta.env.VITE_PUBLIC_BASEURL}/auth/google/redirect`;
            window.open(url , "_blank" , 'width=500; height=500; resizeable: none ; scrollbar=no');
        } else {
            window.location.href = `${import.meta.env.VITE_PUBLIC_BASEURL}/auth/google/redirect`;
        }
  };
    const [loading, setLoading] = useState<boolean>(false);
    const [ispassword, setIsPassword] = useState<boolean>(true);
    const useSelector = useAppSelector((state) => state.userauth);
    const [email, setEmail] = useState<string>('');
    const [password, setPassword] = useState<string>('');
    const [status, setStatus] = useState<string | undefined | string[]>('');
    const AppDispatch = useAppDispatch();
    const HandleLogin = async (e: FormEvent) => {
        e.preventDefault();
        try {
            setLoading(true);
            if (email.length < 3) {
                setStatus('username harus lebih dari 3 karakter');
                return;
            }
            if (password.length < 3) {
                setStatus('password harus lebih dari 3 karakter');
                return;
            }
            if (!email.includes('@') || !email.includes('.com')) {
                setStatus('Format email harus benar');
                return;
            }
            const kirim = {
                email,
                password,
            }
            const res = await AppDispatch(login(kirim));
            if (res.meta.requestStatus == "rejected" && res.payload) {
                setStatus(useSelector.error);
            }
            await AppDispatch(profile()).unwrap();
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
                                <input id="email" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type="text" disabled={loading} onChange={(e) => setEmail(e.target.value)} placeholder="" />
                                <label htmlFor="email" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">email</label>
                            </div>
                            <div className="relative z-0 mb-5 group h-10 max-w-96 w-auto min-w-80 ">
                                <input id="password" autoComplete="on" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type={ispassword ? 'password' : 'text'} disabled={loading} onChange={(e) => setPassword(e.target.value)} placeholder="" />
                                <label htmlFor="password" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">password</label>
                                <h1 className=" absolute right-0 h-12 flex items-center justify-center  w-10" onClick={(e) => { e.preventDefault(); setIsPassword(!ispassword) }}>{ispassword ? <FaEyeSlash /> : <FaEye />}</h1>
                            </div>
                            <button type="submit" className=" bg-blue-600 rounded-2xl py-2 font-bold text-white " disabled={loading}>{loading ? 'loading' : 'send'}</button>
                        </form>
                        <div className="outline-2 outline-gray-900/35 rounded-xl py-3 gap-3 items-center flex mt-4 justify-center bg-gray-600">
                            <BsGoogle color="white" className="mt-1"></BsGoogle>
                            <button onClick={handleGoogleLogin} className="text-white ">Login via google </button>
                        </div>
                    </div>
                </div>
            </main>
        </>
    );
}