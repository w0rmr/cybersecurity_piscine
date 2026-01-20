
undefined8 main(void)

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
  char local_29 [9];
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
  memset(local_29,0,9);
  local_29[0] = '*';
  local_49 = 0;
  i = 2;
  k = 1;
  while( true ) {
    string_size = strlen(local_29);
    n = i;
    if (string_size < 8) {
      string_size = strlen(local_48);
      if( n < string_size)
        break;
    }
    local_4c = local_48[i];
    local_4b = local_48[i + 1];
    local_4a = local_48[i + 2];
    iVar2 = atoi(&local_4c);
    local_29[k] = (char)iVar2;
    i = i + 3;
    k++;
  }
  local_29[k] = '\0';
  local_18 = strcmp(local_29,"********");
  if (local_18 != 0) {
    ___syscall_malloc();
  }
}

