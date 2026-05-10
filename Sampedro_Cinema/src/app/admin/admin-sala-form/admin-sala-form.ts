import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-sala-form',
  standalone: false,
  templateUrl: './admin-sala-form.html',
  styleUrl: './admin-sala-form.css'
})
export class AdminSalaForm implements OnInit {

  sala: any = {
    numero: null,
    capacidad: null
  };

  modoEditar = false;
  idSala: number | null = null;

  cargando = false;
  guardando = false;
  error = '';
  mensaje = '';

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    const id = this.route.snapshot.paramMap.get('id');

    if (id) {
      this.modoEditar = true;
      this.idSala = Number(id);
      this.cargarSala(this.idSala);
    }
  }

  cargarSala(id: number): void {
    this.cargando = true;
    this.error = '';

    this.adminService.obtenerSala(id).subscribe({
      next: (datos) => {
        this.sala = {
          numero: datos.numero,
          capacidad: datos.capacidad
        };

        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar sala:', error);
        this.error = 'No se pudo cargar la sala.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  guardarSala(): void {
    this.guardando = true;
    this.error = '';
    this.mensaje = '';

    if (this.modoEditar && this.idSala !== null) {
      this.adminService.modificarSala(this.idSala, this.sala).subscribe({
        next: () => {
          this.mensaje = 'Sala modificada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/salas']);
        },
        error: (error) => {
          console.error('Error al modificar sala:', error);
          this.error = error.error?.mensaje || 'No se pudo modificar la sala.';
          this.guardando = false;
          this.cd.detectChanges();
        }
      });
    } else {
      this.adminService.crearSala(this.sala).subscribe({
        next: () => {
          this.mensaje = 'Sala creada correctamente.';
          this.guardando = false;
          this.router.navigate(['/admin/salas']);
        },
        error: (error) => {
          console.error('Error al crear sala:', error);
          this.error = error.error?.mensaje || 'No se pudo crear la sala.';
          this.guardando = false;
          this.cd.detectChanges();
        }
      });
    }
  }
}