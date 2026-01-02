export type userStoreType = {
  status: "idle" | "loading" | "authenticated";
  user: user;
  isLogin : boolean;
  error : string[] | string | undefined;
  token : string | null;
};

export type user = {
  id: number | null;
  name: string | null;
  error : string[] | string | undefined;
  isLogin : boolean;
  token : string | null;
} | null;


export type errorTemplate = {
  status : string ,
  message : string[] | string ,
  error : string ,
} | null;

export type stringOrArra = string | string[];