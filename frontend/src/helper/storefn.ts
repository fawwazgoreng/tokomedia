import { useDispatch, useSelector, type TypedUseSelectorHook } from "react-redux";
import type { AppDispatch, AppSelector } from "../store";


export const useAppSelector : TypedUseSelectorHook<AppSelector> = useSelector;
export const useAppDispatch : () => AppDispatch   = useDispatch;