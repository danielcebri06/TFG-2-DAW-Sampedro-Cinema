import { ChangeDetectorRef, Component, OnInit } from '@angular/core';
import { Admin } from '../../services/admin';

@Component({
  selector: 'app-admin-sesiones',
  standalone: false,
  templateUrl: './admin-sesiones.html',
  styleUrl: './admin-sesiones.css',
})
export class AdminSesiones implements OnInit {

  sesiones: any[] = [];
  cargando: boolean = true;
  error: string = '';

  constructor(
    private adminService: Admin,
    private cd: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.cargarSesiones();
  }

  cargarSesiones(): void {
    this.cargando = true;
    this.error = '';

    this.adminService.listarSesiones().subscribe({
      next: (datos) => {
        this.sesiones = datos;
        this.cargando = false;
        this.cd.detectChanges();
      },
      error: (error) => {
        console.error('Error al cargar sesiones:', error);
        this.error = 'No se pudieron cargar las sesiones.';
        this.cargando = false;
        this.cd.detectChanges();
      }
    });
  }

  eliminarSesion(id: number): void {
    const confirmar = confirm('¿Seguro que quieres eliminar esta sesión?');

    if (!confirmar) {
      return;
    }

    this.adminService.eliminarSesion(id).subscribe({
      next: () => {
        this.cargarSesiones();
      },
      error: (error) => {
        console.error('Error al eliminar sesión:', error);
        this.error = 'No se pudo eliminar la sesión.';
        this.cd.detectChanges();
      }
    });
  }
}
