import {Component, OnInit} from "@angular/core";
import {HttpClient} from "@angular/common/http";
import {environment} from "../../environments/environment";
import {Router} from "@angular/router";

@Component({
  selector: "app-products",
  templateUrl: "./products.component.html",
  styleUrls: ["./products.component.css"]
})
export class ProductsComponent implements OnInit {
  products = [];
  file: any;
  error: any;
  username: any;

  constructor(private http: HttpClient, private route: Router) {
  }

  ngOnInit() {
    this.username = localStorage.getItem('user');
    this.getProducts();
  }

  getProducts() {
    this.http.get(`${environment.apiHost}/products.php`)
      .subscribe((response: any) => {
        this.products = response.data;
      }, (error) => {
        console.error(error);
        if (error.status === 401)
          this.route.navigate(["login"]);
      });
  }

  remove(p) {
    this.http.delete(`${environment.apiHost}/products.php?id=${p.id}`)
      .subscribe(() => {
        this.getProducts();
      }, (error) => {
        console.error(error);
      });
  }

  uploadFile(event) {
    let elem = event.target;
    if (elem.files.length > 0) {
      this.file = elem.files[0];
    } else {
      this.file = null;
    }
  }

  import() {
    if (!this.file)
      return;

    let formData = new FormData();
    formData.append("file", this.file);
    this.http.post(`${environment.apiHost}/import.php`, formData)
      .subscribe(() => {
        this.getProducts();
      }, (error) => {
        console.error(error);
        this.error = error.error.message;
      });
  }

  logout() {
    localStorage.clear();
    this.route.navigate(['login']);
  }
}
