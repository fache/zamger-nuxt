export default function({store, redirect}){
    if(store.getters.korisnikLogiran=="neaktivan"){
        return redirect('/login');
    }
}