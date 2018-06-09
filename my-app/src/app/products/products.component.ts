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

  constructor(private http: HttpClient, private route: Router) {
  }

  ngOnInit() {
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
}
