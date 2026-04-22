import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CineService } from '../services/cine';

@Component({
  selector: 'app-reserva-entradas',
  standalone: false,
  templateUrl: './reserva-entradas.html',
  styleUrls: ['./reserva-entradas.css']
})
export class ReservaEntradasComponent implements OnInit {
  pelicula: any;
  sesiones: any[] = [];
  sesionSeleccionada: any = null;
  asientosDeLaSala: any[] = [];
  asientosSeleccionados: any[] = [];

  constructor(private cineService: CineService, private route: ActivatedRoute) {}

  ngOnInit() {
    const id = Number(this.route.snapshot.paramMap.get('id'));
    this.cineService.getPelicula(id).subscribe(p => this.pelicula = p);
    this.cineService.getSesiones(id).subscribe(s => this.sesiones = s);
  }

  alSeleccionarSesion(event: any) {
    const idSesion = Number(event.target.value);
    this.sesionSeleccionada = this.sesiones.find(s => s.id === idSesion);
    this.asientosSeleccionados = []; 
    if (this.sesionSeleccionada) {
      this.cineService.getAsientos(this.sesionSeleccionada.id).subscribe(a => this.asientosDeLaSala = a);
    } else {
      this.asientosDeLaSala = [];
    }
  }

  seleccionarAsiento(asiento: any) {
    if (asiento.estado === 'Ocupado') return;
    if (asiento.estado === 'Libre') {
      asiento.estado = 'Reservado';
      this.asientosSeleccionados.push(asiento);
    } else {
      asiento.estado = 'Libre';
      this.asientosSeleccionados = this.asientosSeleccionados.filter(a => a.id !== asiento.id);
    }
  }

  get precioTotal() {
    return this.sesionSeleccionada ? this.asientosSeleccionados.length * this.sesionSeleccionada.precio : 0;
  }

  confirmarCompra() {
    if (this.asientosSeleccionados.length === 0) return alert('Selecciona un asiento primero.');
    alert(`¡Compra confirmada! Total: ${this.precioTotal}€`);
  }
}