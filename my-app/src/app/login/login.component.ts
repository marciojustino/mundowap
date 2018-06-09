import {Component} from "@angular/core";
import {environment} from "../../environments/environment";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";

@Component({
  selector: "app-login",
  templateUrl: "./login.component.html",
  styleUrls: ["./login.component.css"]
})
export class LoginComponent {
  username;
  password;
  error: any;

  constructor(private http: HttpClient,
              private route: Router) {
  }

  login() {
    const data = {
      username: this.username,
      password: this.password
    };

    this.http.post(`${environment.apiHost}/login.php`, data)
      .subscribe((response: any) => {
        localStorage.setItem("user", response.data.user);
        this.route.navigate(["products"]);
      }, (error) => {
        console.error(error);
        this.error = error.error.message;
      });
  }

}
