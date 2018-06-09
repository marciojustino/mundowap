import {BrowserModule} from "@angular/platform-browser";
import {NgModule} from "@angular/core";
import {AppComponent} from "./app.component";
import {LoginComponent} from "./login/login.component";
import {HeaderComponent} from "./header/header.component";
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";
import {ProductsComponent} from "./products/products.component";
import {RouterModule, Routes} from "@angular/router";
import {AuthGuardService} from "./auth-guard-service.service";

const appRoutes: Routes = [
  {path: "login", component: LoginComponent},
  {path: "products", component: ProductsComponent, canActivate: [AuthGuardService]},
  {
    path: "",
    redirectTo: "products",
    pathMatch: "full"
  },
  {path: "**", component: ProductsComponent}
];

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    HeaderComponent,
    ProductsComponent
  ],
  imports: [
    FormsModule,
    BrowserModule,
    HttpClientModule,
    RouterModule.forRoot(appRoutes)
  ],
  providers: [AuthGuardService],
  bootstrap: [AppComponent]
})
export class AppModule {
}
