import { useState, type FormEvent } from "react";
import { useAppDispatch, useAppSelector } from "@/helper/storefn";
import { register } from "@/store/user";
import { FaEye, FaEyeSlash } from "react-icons/fa";
import { useNavigate} from "react-router-dom";
import { userOtp } from "@/helper/auth/userotp";

export default function RegisterPage() {
    const navi = useNavigate();
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
    const [name, setName] = useState<string>('');
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
                name
            }
            const res = await AppDispatch(register(kirim));
            if (res.meta.requestStatus == "rejected" && res.payload) {
                setStatus(useSelector.error);
                return;
            }
            navi("/verify-otp");
        } finally {
            setLoading(false);
        }
    }

    return (
        <>
            <main className=" h-screen flex flex-col justify-center">
                <div className="container bg-white rounded-2xl shadow-2xl w-xl flex mx-auto py-10">
                    <div className="flex flex-col mx-auto text-2xl ">
                        <h1 className="text-center text-blue-500 font-bold text-4xl mt-4 ">Tokomedia</h1>
                        {status ?
                            <p className={"my-3 max-w-72 text-red-800"}>{status}</p>
                            : <p className="my-3 w-full"></p>}
                        <form action="" onSubmit={(e) => HandleLogin(e)} className="flex flex-col gap-3">
                            <div className="relative z-0 mb-5 group h-10 w-72">
                                <input id="name" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type="text" disabled={loading} onChange={(e) => setName(e.target.value)} placeholder="" />
                                <label htmlFor="name" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">name</label>
                            </div>
                            <div className="relative z-0 mb-5 group h-10 w-72">
                                <input id="email" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type="text" disabled={loading} onChange={(e) => setEmail(e.target.value)} placeholder="" />
                                <label htmlFor="email" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">email</label>
                            </div>
                            <div className="relative z-0 mb-5 group h-10 w-72 ">
                                <input id="password" autoComplete="on" className=" text-sm w-full px-2 py-4 absolute outline-2 shadow-inner outline-gray-400 rounded-xl text-black peer " type={ispassword ? 'password' : 'text'} disabled={loading} onChange={(e) => setPassword(e.target.value)} placeholder="" />
                                <label htmlFor="password" className="peer-placeholder-shown:translate-y-3 peer-focus:not-placeholder-shown:translate-y-0 peer-focus:not-placeholder-shown:text-sm peer-placeholder-shown:text-xl text-sm translate-y-0
                                transform duration-200 absolute translate-x-2 text-teal-900 ">password</label>
                                <h1 className=" absolute right-0 h-12 flex items-center justify-center  w-10" onClick={(e) => { e.preventDefault(); setIsPassword(!ispassword) }}>{ispassword ? <FaEyeSlash /> : <FaEye />}</h1>
                            </div>
                            <button type="submit" className=" bg-blue-600 rounded-2xl py-2 font-bold text-white " disabled={loading}>{loading ? 'loading' : 'send'}</button>
                        </form>
                        <div>
                            <button onClick={handleGoogleLogin}>Login via google </button>
                        </div>
                    </div>
                </div>
            </main>
        </>
    );
}