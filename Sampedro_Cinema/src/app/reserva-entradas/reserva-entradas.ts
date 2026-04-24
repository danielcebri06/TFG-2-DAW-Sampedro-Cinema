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
    // Cargamos sesiones reales desde PHP
    this.cineService.getSesiones(id).subscribe(s => this.sesiones = s);
  }

  alSeleccionarSesion(event: any) {
    const idSesion = Number(event.target.value);
    this.sesionSeleccionada = this.sesiones.find(s => s.id === idSesion);
    this.asientosSeleccionados = []; 
    
    if (this.sesionSeleccionada) {
      //  Pasamos el ID de la sesión y el ID de la sala física
      this.cineService.getAsientos(this.sesionSeleccionada.id, this.sesionSeleccionada.id_sala)
          .subscribe(a => this.asientosDeLaSala = a);
    } else {
      this.asientosDeLaSala = [];
    }
  }

  seleccionarAsiento(asiento: any) {
    if (asiento.estado === 'Ocupado') return; // Si viene ocupado de BD, no hace nada
    
    if (asiento.estado === 'Libre') {
      asiento.estado = 'Reservado'; // Lo marcamos visualmente
      this.asientosSeleccionados.push(asiento);
    } else {
      asiento.estado = 'Libre'; // Lo desmarcamos
      this.asientosSeleccionados = this.asientosSeleccionados.filter(a => a.id !== asiento.id);
    }
  }

  get precioTotal() {
    return this.sesionSeleccionada ? this.asientosSeleccionados.length * this.sesionSeleccionada.precio : 0;
  }

  confirmarCompra() {
    if (this.asientosSeleccionados.length === 0) return alert('Selecciona un asiento primero.');
    alert(`¡Reserva lista! IDs de DB a guardar: ${this.asientosSeleccionados.map(a => a.id_real_db).join(', ')}`);
  }
}