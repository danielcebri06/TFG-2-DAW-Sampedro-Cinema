import { Component, OnInit } from '@angular/core';
import { CineService } from '../services/cine';

@Component({
  selector: 'app-cartelera',
  standalone: false,
  templateUrl: './cartelera.html'
})
export class CarteleraComponent implements OnInit {
  peliculas: any[] = [];
  constructor(private cineService: CineService) {}
  ngOnInit() {
    this.cineService.getPeliculas().subscribe(p => this.peliculas = p);
  }
}