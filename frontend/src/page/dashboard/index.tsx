import { useState, type FormEvent } from "react";
import { Outlet, useNavigate } from "react-router-dom";
import { BiCart, BiSearch } from "react-icons/bi";
import { FaHistory } from "react-icons/fa";
import { useAppDispatch, useAppSelector } from "@/helper/storefn";
import { FaArrowRightFromBracket } from "react-icons/fa6";
import { logout } from "@/store/user";

export default function LayoutDashboard() {
    const navigate = useNavigate();
    const [search, setSearch] = useState<string>('');
    const user = useAppSelector((state) => state.userauth.user)
    const [blur, setBlur] = useState<boolean>(false);
    const AppDispacth = useAppDispatch();
    const handleSearch = (e: FormEvent) => {
        e.preventDefault();
        if (search.length > 1) {
            return;
        }
        navigate(`/user/product?name=${search}`)
    }
    console.log(user);
    const handleLogLout = async () => {
        await AppDispacth(logout());
    }
    return (
        <>
            <nav className=" flex py-3 lg:px-10 w-screen justify-around items-center shadow-2xl">
                <section className="w-1/4 h-10 flex items-center">
                    <h1 className="text-2xl font-bold text-yellow-400">Tokomedia</h1>
                </section>
                <section className=" md:w-1/2 w-1/3 self-start mt-1.5">
                    <div className="relative w-full">
                        <form action="" onSubmit={handleSearch}>
                            <button className=" absolute text-2xl top-1 z-10 left-1" type="submit"><BiSearch></BiSearch></button>
                            <input className=" p-1 pl-6 absolute w-full outline-2 rounded-md outline-gray-500" type="text" onChange={(e) => setSearch(e.target.value)} />
                        </form>
                    </div>
                </section>
                <section className="w-1/3 flex justify-around">
                    <div className=" items-center sm:flex hidden gap-4">
                        <BiCart onClick={() => navigate('/user/cart')} className="text-3xl cursor-pointer"></BiCart>
                        <FaHistory onClick={() => navigate('/user/history')} className="text-2xl cursor-pointer"></FaHistory>
                    </div>
                    <div className="  flex items-center gap-2 relative group" onMouseLeave={() => setBlur(false)} onMouseEnter={() => setBlur(true)} >
                        <h1 className="text-yellow-500 text-2xl md:flex hidden truncate font-semibold">{user?.name}</h1>
                        <span className=" block bg-blue-500 rounded-full w-10 h-10 peer">
                            {/* <img src="" alt="" /> */}
                        </span>
                        <button onClick={handleLogLout} className=" right-0 min-w-40 cursor-pointer hover:flex z-20 group-hover:flex hidden absolute top-full  shadow-2xl rounded-b-2xl bg-white w-full text-xl px-5 py-4 items-center gap-2"><FaArrowRightFromBracket className="font-semibold text-2xl"></FaArrowRightFromBracket><p>Logout</p></button>
                    </div>
                </section>
            </nav>
            {blur ?
                <main className=" blur-2xl  ">
                    <Outlet></Outlet>
                </main>
                :
                <main >
                    <Outlet></Outlet>
                </main>
            }
        </>
    );
}