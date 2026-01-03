import { useEffect, useState } from 'react';
import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { useAppDispatch, useAppSelector } from '@/helper/storefn';
import { initCsrf, profile, refresh } from '@/store/user';
import LoginPage from '@/page/login/login';
import HomePage from '@/page/dashboard/home';
import LayoutDashboard from '@/page/dashboard';
import { Loading } from '@/components/loading';
import { CallBacks } from '@/components/callback';
import RegisterPage from '@/page/login/register';

function App() {
  const [isInitializing, setIsInitializing] = useState(true);
  const appDispatch = useAppDispatch();
  const { isLogin } = useAppSelector((state) => state.userauth);
  const channell = new BroadcastChannel("my_channel");
  useEffect(() => {
    channell.onmessage = (event) => {
      if (event.data !== "refresh") return;
      window.location.reload();
    };
  })
  useEffect(() => {
    const initAuth = async () => {
      try {
        await appDispatch(initCsrf()).unwrap();
        await appDispatch(refresh()).unwrap();
        await appDispatch(profile());
      } catch {
        console.log("Sesi tidak ditemukan atau expired");
      } finally {
        setIsInitializing(false);
      }
    };
    initAuth();
  }, [appDispatch]);

  if (isInitializing) {
    return (
      <div className=' h-screen flex items-center'>
        <Loading></Loading>
      </div>
    );
  }

  return (
    <Router>
      <Routes>
        <Route
          path="/"
          element={isLogin ? <Navigate to="/user" /> : <LoginPage />}
        />
        <Route
          path="/register"
          element={isLogin ? <Navigate to="/user" /> : <RegisterPage />}
        />
        <Route path="/user/*" element={<LayoutDashboard />}>
          <Route
            index
            element={isLogin ? <HomePage /> : <Navigate to="/" />}
          />
          <Route
            path="cart"
            element={isLogin ? <h1>ini cart</h1> : <Navigate to="/" />}
          />
        </Route>
        <Route path="/auth/success" element={<CallBacks />} />
        <Route path="*" element={<Navigate to="/" />} />
      </Routes>
    </Router>
  );
}

export default App;