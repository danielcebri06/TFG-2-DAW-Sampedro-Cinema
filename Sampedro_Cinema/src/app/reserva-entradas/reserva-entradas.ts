import { Component, OnInit, ChangeDetectorRef } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { CineService } from '../services/cine';
import { forkJoin, TimeoutError } from 'rxjs';

@Component({
  selector: 'app-reserva-entradas',
  standalone: false,
  templateUrl: './reserva-entradas.html',
  styleUrls: ['./reserva-entradas.css']
})
export class ReservaEntradasComponent implements OnInit {
  pelicula: any = null;
  sesiones: any[] = [];
  sesionSeleccionada: any = null;
  asientosDeLaSala: any[] = [];
  asientosSeleccionados: any[] = [];
  cargando = true;
  errorCarga = '';

  constructor(
    private cineService: CineService,
    private route: ActivatedRoute,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit() {
    this.route.paramMap.subscribe((params) => {
      const id = Number(params.get('id'));

      if (!id) {
        this.cargando = false;
        this.errorCarga = 'La pelicula solicitada no es valida.';
        this.cdr.detectChanges();
        return;
      }

      this.cargando = true;
      this.errorCarga = '';
      this.pelicula = null;
      this.sesiones = [];
      this.sesionSeleccionada = null;
      this.asientosDeLaSala = [];
      this.asientosSeleccionados = [];

      forkJoin({
        pelicula: this.cineService.getPelicula(id),
        sesiones: this.cineService.getSesiones(id)
      }).subscribe({
        next: ({ pelicula, sesiones }) => {
          this.pelicula = pelicula;
          this.sesiones = sesiones;
          this.cargando = false;
          this.cdr.detectChanges();
        },
        error: (error) => {
          console.error('Error al cargar la reserva:', error);
          if (error instanceof TimeoutError) {
            this.errorCarga = 'El servidor tardó demasiado en responder. ¿Está MySQL corriendo?';
          } else {
            this.errorCarga = 'No se pudieron cargar los detalles de la pelicula.';
          }
          this.cargando = false;
          this.cdr.detectChanges();
        }
      });
    });
  }

  alSeleccionarSesion(event: any) {
    const idSesion = Number(event.target.value);
    this.sesionSeleccionada = this.sesiones.find(s => s.id === idSesion);
    this.asientosSeleccionados = []; 
    
    if (this.sesionSeleccionada) {
      //  Pasamos el ID de la sesión y el ID de la sala física
      this.cineService.getAsientos(this.sesionSeleccionada.id, this.sesionSeleccionada.id_sala)
          .subscribe({
            next: (a) => {
              this.asientosDeLaSala = a;
            },
            error: (error) => {
              console.error('Error al cargar asientos:', error);
              this.asientosDeLaSala = [];
            }
          });
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