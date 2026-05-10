import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-salas',
  standalone: false,
  templateUrl: './admin-salas.html',
  styleUrl: './admin-salas.css',
})
export class AdminSalas implements OnInit {

  salas: any[] = [];
  cargando: boolean = true;
  error: string = '';

  constructor(
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarSalas();
  }

  cargarSalas(): void {
    this.cargando = true;
    this.error = '';

    this.adminService.listarSalas().subscribe({
      next: (datos) => {
        this.salas = datos;
        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar salas:', error);
        this.error = 'No se pudieron cargar las salas.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  eliminarSala(id: number): void {
    const confirmar = confirm('¿Seguro que quieres eliminar esta sala?');

    if (!confirmar) {
      return;
    }

    this.adminService.eliminarSala(id).subscribe({
      next: () => {
        this.cargarSalas();
      },
      error: (error) => {
        console.error('Error al eliminar sala:', error);
        this.error = error.error?.mensaje || 'No se pudo eliminar la sala. Puede que tenga sesiones asociadas.';
        this.cd.detectChanges();
      }
    });
  }

}
