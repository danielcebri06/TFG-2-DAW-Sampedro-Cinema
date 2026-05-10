import { ComponentFixture, TestBed } from '@angular/core/testing';

import { AdminSalaForm } from './admin-sala-form';

describe('AdminSalaForm', () => {
  let component: AdminSalaForm;
  let fixture: ComponentFixture<AdminSalaForm>;

  beforeEach(async () => {
    await TestBed.configureTestingModule({
      declarations: [AdminSalaForm],
    }).compileComponents();

    fixture = TestBed.createComponent(AdminSalaForm);
    component = fixture.componentInstance;
    await fixture.whenStable();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
