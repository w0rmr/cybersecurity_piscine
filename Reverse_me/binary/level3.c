
undefined8 main(void)
//052
{
  ulong n;
  int iVar2;
  size_t string_size;
  bool stop;
  char local_4c;
  char local_4b;
  char local_4a;
  undefined1 local_49;
  char local_48 [31];
  char rslt [9];
  ulong i;
  int local_18;
  int k;
  int local_10;
  undefined4 local_c;
  // 42
  local_c = 0;
  printf("Please enter key: ");
  local_10 = __isoc99_scanf(&DAT_00102056);
  if (local_10 != 1) {
    ___syscall_malloc();
  }
  if (local_48[1] != '2') {
    ___syscall_malloc();
  }
  if (local_48[0] != '4') {
    ___syscall_malloc();
  }
  fflush(_stdin);
  memset(rslt,0,9);
  rslt[0] = '*';
  local_49 = 0;
  i = 2; // increment by 3
  k = 1;// increment by 1
  while( true ) {
    string_size = strlen(rslt);
    n = i;
    if (string_size < 8) { // beak uf last
      string_size = strlen(local_48);
      if( n < string_size)
        break;
    }
    local_4c = local_48[i];
    iVar2 = atoi(&local_4c);
    rslt[k] = (char)iVar2;
    i = i + 3; // increment by 3
    k++;
  }
  rslt[k] = '\0';
  local_18 = strcmp(rslt,"********");
  if (local_18 != 0) {
    ___syscall_malloc();
  }
}

// 4252052052052052052052