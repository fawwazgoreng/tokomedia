import type { templateType } from "./global";

export interface cartType {
  id: number;
  namaproduct: string;
  jumlah: number;
}

export interface cartApi extends templateType {
  data: cartType[];
}

export type cartState = {
  data: cartType[];
  status: "idle" | "loading" | "succeded" | "failed";
  error: null | string;
};
