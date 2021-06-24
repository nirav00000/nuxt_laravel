export default function ({ store, redirect, app }) {
  if (!store.getters.getToken) {
    app.$cookies.remove("token");

    return redirect("/login");
  }
}
